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

/**
 * This class implements the {@see StreamInterface} and is backed by a native array.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Stream implements StreamInterface
{
    /**
     * A collection of elements on which to operate.
     *
     * @var array
     */
    private $elements = array();
    
    /**
     * The number of elements contained within the stream.
     *
     * @var int
     */
    private $size = 0;
    
    /**
     * An internal pointer.
     *
     * @var int
     */
    public $index = 0;

    /**
     * Construct a Stream.
     *
     * @param array $elements a collection of elements on which the stream will operate.
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
        $this->size     = count($elements);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return (isset($this->elements[$this->index])) ? $this->elements[$this->index] : null;
    }
     
    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return ($this->index < $this->size);
    }
    
    /**
     * {@inheritDoc}
     */
    public function consume()
    {
        $element = $this->current();
        $this->index++;
        
        return $element;
    }
    
    /**
     * {@inheritDoc}
     */
    public function skip($number = 1)
    {
        if (!is_numeric($number)) {
           throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));
        } else if ($number <= 0) {
           throw new \LogicException(sprintf(
                '%s: expects a positive number; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));  
        }
        
        $this->index += (int) $number;
        return $this->valid();
    }
    
    /**
     * {@inheritDoc}
     */
    public function peek($number = 1)
    {
        if (!is_numeric($number)) {
           throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));
        } else if ($number <= 0) {
           throw new \LogicException(sprintf(
                '%s: expects a positive number; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));  
        }
        
        $index = $this->index + (int) $number;
        return (isset($this->elements[$index])) ? $this->elements[$index] : null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function until($predicate, $consume = true)
    {
        $collection = array();
        $index      = $this->index;

        for ($index; $index < $this->size; $index++) {
            $collection[] = $this->elements[$index];
            if ($predicate($this->elements[$index])) {
                break;
            }
        }
        
        if ($consume) {
            $this->index = ++$index;
        }
        
        return new Stream($collection);
    }
    
    /**
     * {@inheritDoc}
     */
    public function filter($predicate)
    {
        $elements   = $this->toArray();
        $collection = array();

        foreach ($elements as $element) {
            if ($predicate($element)) {
                $collection[] = $element;
            }
        }
        
        return new self($collection);
    }
    
    /**
     * {@inheritDoc}
     */
    public function limit($size)
    {        
        if (is_numeric($number)) {
           throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));
        } else if ($number <= 0) {
           throw new \LogicException(sprintf(
                '%s: expects a positive number; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));  
        }
    
        $length = ($size < $this->size) ? $size : null;
        $collection = array_slice($this->elements, 0, $length);
        
        return new Stream($collection);
    }
    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * Returns the number of elements contained within the stream.
     *
     * @return int the number of elements.
     */
    public function count()
    {
        return $this->size;
    }
}
