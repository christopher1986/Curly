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

namespace Curly\Collection\Iterator;

use SeekableIterator;

use Curly\Collection\ListInterface;
use Curly\Collection\Exception\NoSuchElementException;

/** 
 * The ListIterator can be used to traverse a list in either direction, seek a position within the list,
 * and obtain the iterator's current position in the list. The iterator internally uses a pointer which 
 * should always lie between 0 and the maximum number of elements contained by the list. Calling methods
 * such as {@link ListIterator::next()} and {@link ListIterator::prev} on the iterator without actually 
 * checking if the pointer is still valid will eventually lead {@link NoSuchElementException}.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ListIterator implements SeekableIterator
{    
    /**
     * A list of elements.
     *
     * @var ListInterface
     */
    private $list;

    /**
     * Internal pointer of this iterator.
     *
     * @var int
     */
    private $position = 0;

    /**
     * Construct a new ListIterator.
     *
     * @param ListInterface $list the list to be iterated on.
     */
    public function __construct(ListInterface $list)
    {
        $this->list = $list; 
    }

    /**
     * Seeks to a given position in the iterator.
     *
     * @param int $position the position to seek to.
     * @throws InvalidArgumentException if the given argument is not a numeric value.
     * @throws OutOfRangeException if the specified position is out of range.
     */
    public function seek($position)
    {
        if (!is_int($position)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an integer argument; received "%s"',
                __METHOD__,
                (is_object($position) ? get_class($position) : gettype($position))
            ));
        } else if ($position < 0 || $position >= count($this->list)) {
            throw new \OutOfRangeException(sprintf(
                '%s: list size: %d; received position %s',
                __METHOD__, 
                $this->count(),
                $position
            ));
        }
        
        $this->position = $position;
    }
    
    /**
     * Returns the current element.
     *
     * @return mixed the current element, or null on failure.
     */
    public function current()
    {
        $element = null;
        if ($this->valid()) {
            $element = $this->list->get($this->position);
        }
        
        return $element;
    }
    
    /**
     * Returns the index of the current element.
     *
     * @return int the index of the current element.
     */    
    public function key()
    {
        return $this->position;
    }
    
    /**
     * Rewind the iterator back to the first element.
     */
    public function rewind()
    {
        $this->position = 0;
    }
    
    /**
     * Returns true if the current position is valid.
     *
     * @return bool true if the current position is valid, false otherwise.
     */
    public function valid()
    {
        return ($this->hasPrev() && $this->hasNext());
    }
    
    /**
     * Move forward to the next element.
     *
     * @throw NoSuchElementException if the iterator can not go forward anymore.
     */ 
    public function next()
    {
        if (!$this->hasNext()) {
            throw new NoSuchElementException(sprintf(
                '%s: there are no more elements left to iterate over.',
                __METHOD__ 
            ));
        }
    
        ++$this->position;
    }
    
    /**
     * Returns true if there are still elements left to iterate over.
     *
     * @return bool true if there still elements left, false otherwise.
     */
    public function hasNext()
    {
        return ($this->position < count($this->list));
    }
    
    /**
     * Move backwards to the previous element.
     *
     * @throw NoSuchElementException if the iterator can not go back anymore.
     */ 
    public function prev()
    {
        if (!$this->hasPrev()) {
            throw new NoSuchElementException(sprintf(
                '%s: there are no previous elements left to iterate over.',
                __METHOD__ 
            ));
        }
        
        --$this->position;
    }
    
    /**
     * Returns true if the iterator is capable of going back to the previous element.
     *
     * @return bool true if the iterator can go backwards, false otherwise.
     */
    public function hasPrev()
    {
        return ($this->position >= 0);
    }
}
