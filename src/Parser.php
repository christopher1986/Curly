<?php

namespace Curly;

use Curly\Ast\Node;
use Curly\Ast\Node\Expression\TextNode;
use Curly\Ast\Node\Expression\VariableNode;
use Curly\Collection\Stream\TokenStream;
use Curly\Parser\Exception\IllegalStateException;
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

        $nodes = array();
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
            } else if ($stream->matches(Token::T_KEYWORD)) {
                $tag = $this->getLibrary()->getTag($token->getValue());
                if ($tag === null) {
                    throw new SyntaxException(sprintf('Encountered an unexpected keyword "%s".', $token->getValue()), $token->getLineNumber());
                }

                $nodes[] = $tag->parse($this, $this->getStream());
            } else {
                $substream = $stream->until(function($token) {
                    return (in_array($token->getType(), array(Token::T_SEMICOLON, Token::T_CLOSE_TAG)));
                });
                $nodes[] = $this->parseExpression($substream);
            }
        }

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
             * Apply recursion as long as the next binary operator
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
            } else if ($stream->matches(Token::T_IDENTIFIER)) {                
                $node = new VariableNode($token->getValue(), $token->getLineNumber());
                $stream->consume();
            } else {
                throw new SyntaxException(sprintf('Illegal identifier "%s".', $token->getValue()), $token->getLineNumber());
            }
        } else {
            throw new SyntaxException(sprintf('Cannot find symbol "%s".', $token->getValue()), $token->getLineNumber());
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
