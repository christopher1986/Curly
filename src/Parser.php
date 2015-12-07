<?php

namespace Curly;

use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Ast\Node\FilterNode;
use Curly\Ast\Node\PrintNode;
use Curly\Ast\Node\TextNode;
use Curly\Ast\Node\Expression\ArrayAccessNode;
use Curly\Ast\Node\Expression\VariableNode;
use Curly\Parser\Exception\SyntaxException;
use Curly\Parser\Stream\TokenStream;
use Curly\Parser\TokenInterface;
use Curly\Parser\Token;

/**
 * The Parser is a concrete implementation of the {@link ParserInterface}.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Parser implements ParserInterface
{
    /**
     * The template engine.
     *
     * @var EngineInterface
     */
    private $engine;

    /**
     * A stream containing tokens to parse.
     *
     * @var TokenStream
     */
    private $stream;
    
    /**
     * Construct a new Parser.
     *
     * @param EngineInterface $engine the template engine.
     */
    public function __construct(EngineInterface $engine)
    {
        $this->setEngine($engine);
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(TokenStream $stream, $until = null)
    {       
        // second argument if provided must be array.
        if (func_get_args() >= 2) {
            $until = (is_array($until)) ? $until : array_slice(func_get_args(), 1);
        }
    
        $this->setStream($stream);

        $nodes = array();
        while ($stream->valid()) {
            // template tags
            $stream->consumeIf(Token::T_OPEN_TAG, Token::T_CLOSE_TAG);

            if ($until && $stream->matches($until)) {
                return $nodes;
            }
            
            // print tags
            if ($token = $stream->consumeIf(Token::T_OPEN_PRINT_TAG)) {
                $nodes[] = new PrintNode($this->parseExpression($stream), $token->getLineNumber());
                $stream->expects(Token::T_CLOSE_PRINT_TAG);
            }
            // plain text
            else if ($token = $stream->consumeIf(Token::T_TEXT)) {
                $nodes[] = new TextNode($token->getValue(), $token->getLineNumber());
            } 
            // statements
            else if ($stream->matches(Token::T_IDENTIFIER)) {
                $statement = $this->getLibrary()->getStatement($stream->current()->getValue());
                if ($statement === null) {
                    throw new SyntaxException(sprintf('Illegal identifier "%s".', $stream->current()->getValue()), $stream->current()->getLineNumber());
                }
                    
                $nodes[] = $statement->parse($this, $this->getStream());
            // expressions
            } else {
                $nodes[] = $this->parseExpression($stream);
                $stream->expects(Token::T_SEMICOLON, Token::T_CLOSE_TAG);
            }
        }
        
        return new Node($nodes);
    }
    
    /**
     * {@inheritDoc}
     */
    public function parseExpression(TokenStream $stream, $precedence = 0)
    {
        if (!$stream->valid()) {
            throw new SyntaxException('Unexpected end of file.');   
        }
            
        $expr = $this->parsePrimaryExpression($stream);

        while (($token = $stream->current()) && $this->isBinary($token)) {       
            $operator = $this->getBinaryOperator($token);
            if ($operator->getPrecedence() < $precedence) {
                break;  
            }
            
            $stream->consume();

            // do recursion while next binary operator has higher precedence than current operator. 
            $expr = $operator->createNode($expr, $this->parseExpression($stream,
			    $operator->isRightAssociative()
                    ? $operator->getPrecedence()
					: $operator->getPrecedence() + 1
			), $token->getLineNumber());
        }

        return $expr; 
    }
    
    /**
     * {@inheritDoc}
     */
    public function parsePrimaryExpression(TokenStream $stream)
    {    
        $node  = null;
        $token = $stream->current();

        // unary operators
        if ($this->isUnary($token) && $stream->consume()) {            
            $operator = $this->getUnaryOperator($token);            
            $node     = $operator->createNode($this->parseExpression($stream, $operator->getPrecedence()), $token->getLineNumber());
        } 
        // parenthesized expression
        else if ($stream->consumeIf(Token::T_OPEN_PARENTHESIS)) {            
            $node = $this->parseExpression($stream);
            $stream->expects(Token::T_CLOSE_PARENTHESIS);
        }
        // template tags
        else if ($stream->matches(Token::T_IDENTIFIER)) {
            $tag = $this->getLibrary()->getTag($token->getValue());
            if ($tag === null) {
                throw new SyntaxException(sprintf('Illegal identifier "%s".', $token->getValue()), $token->getLineNumber());
            }
                
            $node = $tag->parse($this, $this->getStream());
        } 
        // variables
        else if ($stream->matches(Token::T_VARIABLE)) {
            $node = new VariableNode($token->getValue(), $token->getLineNumber());
            $stream->consume();
            
            if ($stream->matches(Token::T_OPEN_BRACKET)) {
                $node = $this->parseArrayAccessExpression($stream, $node);
            } else if ($stream->matches(Token::T_PIPELINE)) {
                $node = $this->parseFilterExpression($stream, $node);
            }
        }
        // literals
        else if ($this->isLiteral($token)) {
            $literal = $this->getLiteral($token);
            $node    = $literal->parse($this, $stream);
            
            if ($stream->matches(Token::T_OPEN_BRACKET)) {
                $node = $this->parseArrayAccessExpression($stream, $node);
            } else if ($stream->matches(Token::T_PIPELINE)) {
                $node = $this->parseFilterExpression($stream, $node);
            }
        }
        
        return $node;
    }
    
    /**
     * Parse an array access expression.
     *
     * The following examples are all valid array access expressions:
     *
     * <code>
     *     $array = {'first' : 'foo', 'second': 'bar'};
     *     $value = $array['first'];
     *     $value = ['foo', 'bar', 'baz'][2];
     *     $value = ['foo', ['foobar', 'foobaz'], 'bar'][1][0]
     * </code>
     *
     * @param TokenStream a stream of tokens to parse.
     * @param NodeInterface the array node to access.
     * @return ArrayAccessNode an array access node.
     * @throws SyntaxException if the current token is not an open bracket.
     */
    private function parseArrayAccessExpression($stream, NodeInterface $node)
    {     
        $token   = $stream->current();   
        $indices = array();
        while ($stream->consumeIf(Token::T_OPEN_BRACKET)) {
            $indices[] = $this->parseExpression($stream);            
            $stream->expects(Token::T_CLOSE_BRACKET);
        }
        
        return new ArrayAccessNode($node, $indices, $token->getLineNumber());
    }
     
    /**
     * Parse a filter expression.
     *
     * The following examples are all valid filter expressions:
     *
     * <code>
     *     $name     = 'foo'|upper;
     *     $pub_date = $pub_date|date('Y-m-d');
     *     $value    = $value|upper|default('baz');
     * </code>
     *
     * @param TokenStream a stream of tokens to parse.
     * @param NodeInterface the node to which a filter is applied.
     * @return NodeInterface a filtered expression node.
     * @throws SyntaxException if the current token is not a pipeline.
     */
    private function parseFilterExpression($stream, NodeInterface $node)
    {        
        $library = $this->getLibrary();
        while ($stream->consumeIf(Token::T_PIPELINE)) {
            $token  = $stream->expects(Token::T_IDENTIFIER);
            $filter = $library->getFilter($token->getValue());
            if (!is_object($filter)) {
                throw new SyntaxException(sprintf('Unexpected "%s" (%s)', $token->getValue(), Token::getLiteral($token->getType())), $token->getLineNumber());
            }

            $args = array();
            if ($stream->consumeIf(Token::T_OPEN_PARENTHESIS)) {
                do {
                    $args[] = $this->parseExpression($stream);
                } while ($stream->consumeIf(Token::T_COMMA));
               
                $stream->expects(Token::T_CLOSE_PARENTHESIS);                
            }
            
            $node = new FilterNode($node, $filter, $args, $token->getLineNumber());
        }
        
        return $node;
    }
    
    /**
     * Set the template engine.
     *
     * @param EngineInterface the template engine.
     */
    private function setEngine(EngineInterface $engine)
    {
        $this->engine = $engine;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getEngine()
    {
        return $this->engine;
    }
    
    /**
     * Set a stream of token to parse.
     *
     * @param TokenStream $stream a stream of tokens.
     */
    private function setStream(TokenStream $stream)
    {
        $this->stream = $stream;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getStream()
    {
        return $this->stream;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLibrary()
    {
        return $this->getEngine()->getLibrary();
    }
    
    /**
     * Returns true if the specified token represents a literal.
     *
     * @param TokenInterface $token the token whose value will be tested.
     * @return bool true if the specified token represents a literal, false otherwise.
     */
    private function isLiteral(TokenInterface $token)
    {
        return ($this->getLiteral($token) !== null);
    }
    
    /**
     * Returns if present a literal for the specified token.
     *
     * @param TokenInterface $token the token for which to find a suitable operator.
     * @return LiteralInterface|null a literal for the specified token, or null on failure.
     */
    private function getLiteral(TokenInterface $token)
    {
        return $this->getLibrary()->getLiteral($token->getType());
    }
    
    /**
     * Returns true if the specified token represents a binary operator.
     *
     * @param TokenInterface $token the token whose value will be tested.
     * @return bool true if the specified token represents a binary operator, false otherwise.
     */
    private function isBinary(TokenInterface $token)
    {
        return ($this->getBinaryOperator($token) !== null);
    }
    
    /**
     * Returns if present a binary operator for the specified token.
     *
     * @param TokenInterface $token the token for which to find a suitable operator.
     * @return BinaryOperator|null a binary operator for the specified token, or null on failure.
     */
    private function getBinaryOperator(TokenInterface $token)
    {
        return $this->getLibrary()->getBinaryOperator($token->getValue());
    }
    
    /**
     * Returns true if the specified token represents a unary operator.
     *
     * @param TokenInterface $token the token whose value will be tested.
     * @return bool true if the specified token represents a unary operator, false otherwise.
     */
    private function isUnary(TokenInterface $token)
    {
        return ($this->getUnaryOperator($token) !== null);
    }
    
    /**
     * Returns if present a unary operator for the specified token.
     *
     * @param TokenInterface $token the token for which to find a suitable operator.
     * @return UnaryOperator|null a unary operator for the specified token, or null on failure.
     */
    private function getUnaryOperator(TokenInterface $token)
    {
        return $this->getLibrary()->getUnaryOperator($token->getValue());
    }
}
