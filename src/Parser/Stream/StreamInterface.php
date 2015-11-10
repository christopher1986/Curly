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

use Countable;

/**
 * A stream is very similar to a collection type in the sense that it stores elements.
 * However streams are not traversable, which in short means that you can not iterate 
 * through the underlying elements like you would do with an array or other traversable
 * object.
 *
 * As an alternative the {@link StreamInterface::current()}, {@link StreamInterface::consume()} 
 * and {@link StreamInterface::valid()} methods can be used to move through the stream.
 *
 * Streams are powerful and preferred when fine-grained control of elements is necessary.
 * A Parser requiring a lookahead of 2 or more tokens for example may benefit from the
 * {@link StreamInterface::peek($number)} method.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface StreamInterface extends Countable
{       
    /**
     * Returns the element at the current stream position.
     *
     * @return mixed|null the element at the current stream position, or null on failure.
     */
    public function current();
     
    /**
     * Returns true if the current stream position is valid.
     *
     * @return bool true if the current stream position is valid, false otherwise.
     */
    public function valid();
    
    /**
     * Consume and return the element at the current stream position.
     *
     * @return mixed|null the element at the current stream position, or null on failure.
     */
    public function consume();
    
    /**
     * Skip the specified amount of elements that lie beyond the current stream position.
     *
     * @param int $number (optional) the number of elements to skip, default is 1.
     * @return bool true if the current stream position is still valid, false otherwise.
     */
    public function skip($number = 1);
    
    /**
     * Returns the element that lies beyond the specified current stream position without moving 
     * the stream position forward.
     *
     * @param int $number (optional)  the number of elements to look beyond, default is 1.
     * @return mixed|null the element that lies beyond the current stream position, or null on failure.
     */
    public function peek($number = 1);
    
    /**
     * Returns a new stream with elements that lie between the current stream position and the
     * first element that matches the specified predicate.
     *
     * @param callable $predicate the predicate to determine where the stream position will stop.
     * @param bool $consume if true will move the stream position forward, otherwise a look-ahead is
     *                      performed which means the stream position of this stream remains unchanged.
     * @return StreamInterface the new stream containing all elements that lie between the current
     *                         stream position and the first element that matches the predicate.
     */
    public function until($predicate, $consume = true);
    
    /**
     * Returns a new stream with elements that match the specified predicate.
     *
     * @param callable $predicate the predicate to determine which elements should be included.
     * @return StreamInterface the new stream.
     */
    public function filter($predicate);
    
    /**
     * Returns a new stream consisting of element contained by this stream, but truncated to the specified size.
     *
     * @param int $size the maximum number of elements allowed within the stream.
     * @return StreamInterface the new stream.
     */
    public function limit($size);
    
    /**
     * Returns an array containing all the elements of this stream.
     *
     * @return array a collection of elements contained within this stream.
     */
    public function toArray();
}
