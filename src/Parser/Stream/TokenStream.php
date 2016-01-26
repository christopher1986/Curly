<?php
/**
 * Copyright (c) 2015, Chris Harris.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of the copyright holder nor the names of its
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author     Chris Harris <c.harris@hotmail.com>
 * @copyright  Copyright (c) 2015 Chris Harris
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */

namespace Curly\Parser\Stream;

use Curly\Parser\Token;
use Curly\Parser\TokenInterface;
use Curly\Parser\Exception\SyntaxException;

/**
 * The TokenStream class is a concrete implementation of the {@link TokenStreamInterface}.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class TokenStream implements TokenStreamInterface
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
     * {@inheritDoc}
     */
    public function consumeIf($types)
    {      
        $types = (is_array($types)) ? $types : func_get_args();
        return ($this->matches($types)) ? $this->consume() : null;
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
     * {@inheritDoc}
     */
    public function matches($types)
    {
        if (!$this->valid()) {
            return false;
        }
                
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
     * {@inheritDoc}
     */
    public function expects($types)
    {    
        $types = (is_array($types)) ? $types : func_get_args();
    
        if (!$this->matches($types)) {
            $names = array();
            foreach ($types as $type) {
                list($type, $value) = array_pad(explode(':', $type), 2, null);
                $names[] = Token::getLiteral($type);
            }
             
            $message = sprintf('Expected one of the following ("%s").', implode('", "', $names));
            $lineno  = ($this->current()) ? $this->current()->getLineNumber() : -1;

            throw new SyntaxException($message, $lineno);
        }
        
        return $this->consume();
    }
}
