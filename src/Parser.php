<?php

namespace Curly;

use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Ast\Node\TextNode;
use Curly\Ast\Node\Expression\VariableNode;
use Curly\Collection\Stream\TokenStream;
use Curly\Parser\Exception\SyntaxException;
use Curly\Parser\TokenInterface;
use Curly\Parser\Token;

/**
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
     * A stream containing tokens.
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
        if (func_get_args() >= 2) {
            $until = (is_array($until)) ? $until : array_slice(func_get_args(), 1);
        }
    
        $this->setStream($stream);

        $library = $this->getLibrary();
        $nodes   = array();
        while ($stream->valid()) {            
            if ($until && $stream->matches($until)) {
                return $nodes;
            }
            
            $token = $stream->current();
            if ($stream->matches(Token::T_TEXT)) {
                $stream->consume();
                $nodes[] = new TextNode($token->getValue(), $token->getLineNumber());
            } else if ($stream->matches(Token::T_OPEN_TAG, Token::T_CLOSE_TAG)) {
                $stream->consume();
            } else if ($stream->matches(Token::T_IDENTIFIER)) {
                if ($tag = $library->getTag($token->getValue())) {
                    $nodes[] = $tag->parse($this, $this->getStream());
                } else {
                    throw new SyntaxException(sprintf('Unexpected "%s" (%s)', $token->getValue(), $token->getLiteral($token->getType())), $token->getLineNumber());
                }
            } else {
                $nodes[] = $this->parseExpression($stream);
                $stream->expects(Token::T_SEMICOLON, Token::T_CLOSE_TAG);
            }
            
            // avoid an infinite loop.
            if ($stream->current() === $token) {
                $stream->consume();
            }
        }
        
        echo '<pre>';
        var_dump($nodes);
        
        return $nodes;
    }
    
    /**
     * Parse a single expression.
     *
     * @param TokenStream a stream of tokens to parse.
     * @param int $precedence (optional) the operator precedence that when exceeded by an operator will add
     *                                   that operator as a child node to the previous node.
     * @return NodeInterface an expression node.
     */
    public function parseExpression(TokenStream $stream, $precedence = 0)
    {
        $expr = $this->parsePrimaryExpression($stream);
        
        while (($token = $stream->current()) && $this->isBinary($token)) {           
            $operator = $this->getBinaryOperator($token);
            if ($operator->getPrecedence() < $precedence) {
                break;  
            }
            
            $stream->consume();

            /*
             * apply recursion as long as the next binary operator
             * has a higher precedence than the current one. 
             */
            $expr = $operator->createNode($expr, $this->parseExpression($stream,
			    $operator->isRightAssociative()
                    ? $operator->getPrecedence()
					: $operator->getPrecedence() + 1
			), $token->getLineNumber());
        }

        return $expr; 
    }
    
    /**
     * Parse a primary expression.
     *
     * @param TokenStream a stream of tokens to parse.
     * @return NodeInterface an expression node.
     */
    private function parsePrimaryExpression(TokenStream $stream)
    {    
        $node  = null;
        $token = $stream->current();

        if ($this->isUnary($token)) {
            $stream->consume();
            $operator = $this->getUnaryOperator($token);            
            $node = $operator->createNode($this->parseExpression($stream, $operator->getPrecedence()), $token->getLineNumber());
        } else if ($stream->matches(Token::T_OPEN_PARENTHESIS)) {
            $stream->consume();
            if (!$stream->valid()) {
                throw new SyntaxException('Unexpected end of file.');   
            }
            
            $node = $this->parseExpression($stream);
            
            if (!$stream->matches(Token::T_CLOSE_PARENTHESIS)) {
                throw new SyntaxException('Expected ")"', $token->getLineNumber());
            }
            
            $stream->consume();
        } else if ($token) {
            $literal = $this->getLiteral($token);
            if ($literal) {
                $node = $literal->parse($this, $stream);
            } else if ($stream->matches(Token::T_VARIABLE)) {                
                $node = new VariableNode($token->getValue(), $token->getLineNumber());
                $stream->consume();
            } else {
                throw new SyntaxException(sprintf('Illegal identifier "%s".', $token->getValue()), $token->getLineNumber());
            }
            
            if ($stream->matches(Token::T_PIPELINE)) {
                $node = $this->parseFilterExpression($stream, $node);
            }
        } else {
            throw new SyntaxException(sprintf('Cannot find symbol "%s".', $token->getValue()), $token->getLineNumber());
        }
        
        return $node;
    }
    
    /**
     * Parse a filter expression.
     *
     * A filter expression is one where a variable or literal node is suffixed with one or more pipelines.
     * The following code snippet shows a string literal being made lowercase using the lower filter.
     *
     * <code>
     *     name = "JOHN"|lower;
     * </code>
     *
     * @param TokenStream a stream of tokens to parse.
     * @param NodeInterface the node to which a filter is applied.
     * @return NodeInterface a filtered expression node.
     */
    private function parseFilterExpression($stream, NodeInterface $node)
    {
        $token = $stream->current();
        if (!$stream->matches(Token::T_PIPELINE)) {
            $lineno = ($token) ? $token->getLineNumber() : -1;
            throw new SyntaxException('Expected ")"', $lineno);
        }
        
        while ($stream->matches(Token::T_PIPELINE)) {
            $stream->consume();
            $token = $stream->expects(Token::T_IDENTIFIER);
            
            if ($stream->matches(Token::T_OPEN_PARENTHESIS)) {
                $stream->consume();
                $params = $this->parseExpression($stream);
                echo '<pre>';
                var_dump($params);
                $stream->expects(Token::T_CLOSE_PARENTHESIS);
            }
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
     * Returns if present an expression for the specified token.
     *
     * @param TokenInterface $token the token for which to find a suitable expression.
     * @return ExpressionInterface|null an expression for the specified token, or null on failure.
     */
    private function getLiteral(TokenInterface $token)
    {        
        $library = $this->getLibrary();
        return $library->getLiteral($token->getType());
    }
    
    /**
     * Returns true if the specified token represents a unary operator.
     *
     * @param TokenInterface $token the token whose value will be tested.
     * @return bool true if the specified token represents a unary operator, false otherwise.
     */
    private function isUnary(TokenInterface $token)
    {
        $library = $this->getLibrary();
        return ($library->getUnaryOperator($token->getValue()) !== null);
    }
    
    /**
     * Returns if present a unary operator for the specified token.
     *
     * @param TokenInterface $token the token for which to find a suitable operator.
     * @return UnaryOperator|null a unary operator for the specified token, or null on failure.
     */
    private function getUnaryOperator(TokenInterface $token)
    {
        $library = $this->getLibrary();
        return $library->getUnaryOperator($token->getValue());
    }
    
    /**
     * Returns if present a binary operator for the specified token.
     *
     * @param TokenInterface $token the token for which to find a suitable operator.
     * @return BinaryOperator|null a binary operator for the specified token, or null on failure.
     */
    private function getBinaryOperator(TokenInterface $token)
    {
        $library = $this->getLibrary();
        return $library->getBinaryOperator($token->getValue());
    }

    /**
     * Returns true if the specified token represents a binary operator.
     *
     * @param TokenInterface $token the token whose value will be tested.
     * @return bool true if the specified token represents a binary operator, false otherwise.
     */
    private function isBinary(TokenInterface $token)
    {    
        $library = $this->getLibrary();
        return ($library->getBinaryOperator($token->getValue()) !== null);
    }
}
