<?php

namespace Curly\Collection\Stream;

use Curly\Parser\TokenInterface;
use Curly\Parser\Exception\SyntaxException;

class TokenStream implements StreamInterface
{
    /**
     * The underlying stream.
     *
     * @var StreamInterface
     */
    public $stream;

    /**
     * Construct a TokenStream.
     *
     * @param StreamInterface $stream the stream to decorate.
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream->filter(function($element) {
            return ($element instanceof TokenInterface);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->stream->current();
    }
     
    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return $this->stream->valid();
    }
    
    /**
     * {@inheritDoc}
     */
    public function consume()
    {
        return $this->stream->consume();
    }
    
    /**
     * Consume and return the token at the current stream position if the token matches any of the specified token types.
     *
     * @param mixed $types one or more possible token types to match.
     * @return TokenInterface|null the token at the current stream position, or null on failure.
     */ 
    public function consumeIf($types)
    {
        $token = null;
        if ($this->matches($types)) {
            $token = $this->stream->consume();
        }
        
        return $token;
    }
    
    /**
     * {@inheritDoc}
     */
    public function skip($number = 1)
    {
        return $this->stream->skip($number);
    }
    
    /**
     * {@inheritDoc}
     */
    public function peek($number = 1)
    {
        return $this->stream->peek($number);
    }
    
    /**
     * {@inheritDoc}
     */
    public function until($predicate, $consume = true)
    {
        return new self($this->stream->until($predicate, $consume));
    }
    
    /**
     * {@inheritDoc}
     */
    public function filter($predicate)
    {
        return new self($this->stream->filter($predicate));
    }
    
    /**
     * {@inheritDoc}
     */
    public function limit($size)
    {                
        return new self($this->stream->limit($size));
    }
    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->stream->toArray();
    }

    /**
     * Returns the number of tokens contained within the stream.
     *
     * @return int the number of tokens.
     */
    public function count()
    {
        return $this->stream->count();
    }
    
    /**
     * Returns true if the current token matches any of the specified token types.
     * A token can be matched on both token type and value or soley on it's token type.
     *
     * <code>
     *     $stream->matches(Token::T_OPERATOR, Token::T_IDENTIFIER);
     *     $stream->matches(sprintf('%s:is', Token::T_OPERATOR), Token::T_IDENTIFIER);
     * </code>
     *
     * @param string|string[] $types one or more possible token types to match.
     * @return bool true if the current token matches with at least one of the specified token types, false otherwise.
     */
    public function matches($types)
    {
        $types = (is_array($types)) ? $types : func_get_args();

        $token = $this->current();
        foreach ($types as $type) {
            list($type, $value) = array_pad(explode(':', $type), 2, null);

            if ($type == $token->getType()) {
                if ($value !== null && $value != $token->getValue()) {
                    continue;
                }
                return true;
            }
        }
    
        return false;
    }

    /**
     * Tests whether the current token within the stream is one of the specified types and moves the current stream position forward.
     *
     * @param string|string[] $types one or more possible token types to match.
     * @return TokenInterface the token that matched one of the specified types.
     * @throws SyntaxException if the current token is not one of the specified types.
     * @see TokenStream::matches($types);
     */
    public function expects($types)
    {
        $types = (is_array($types)) ? $types : func_get_args();
    
        if (!$this->matches($types)) {
            $token = $this->current();
            $names = array();
            foreach ($types as $type) {
                list($type, $value) = array_pad(explode(':', $type), 2, null);
                $names[] = $token->getLiteral($type);
            }
            
            $message = sprintf('Expected one of the following ("%s").', implode('", "', $names));
            if ($this->valid()) {
                throw new SyntaxException($message, $this->current()->getLineNumber());
            } else {
                throw new SyntaxException($message);
            }
        }
        
        return $this->consume();
    }
}
