<?php

namespace Curly\Parser\Stream;

use Countable;

/**
 * 
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
