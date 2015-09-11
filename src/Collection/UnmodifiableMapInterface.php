<?php

namespace Curly\Collection;

use Countable;
use IteratorAggregate;

interface UnmodifiableMapInterface extends Countable, IteratorAggregate
{
    /**
     * Returns the value associated with the specified key, or the default value if this map contains no mapping for the specified key.
     *
     * @param mixed $key the key whose value will be returned.
     * @param mixed $default the value to return if no mapping exists for the specified key.
     * @return mixed the value associated with the specified key, or the $default value if no mapping exists for the key.
     */
    public function get($key, $default = null);
    
    /**
     * Returns true if this map contains the specified key. More formally returns true only if this map
     * contains a key $k such that ($k === $key).
     *
     * @param mixed $key the key whose presence will be tested.
     * @return bool true if this map contains the specified key, false otherwise.
     */ 
    public function containsKey($key);
    
    /**
     * Returns true if this map contains the specified value. More formally returns true only if this map
     * contains a value $v such that ($v === $value).
     *
     * @param mixed $value the value whose presence will be tested.
     * @return bool true if this map contains the specified value, false otherwise.
     */
    public function containsValue($value);
    
    /**
     * Returns true if this map is considered to be empty.
     *
     * @return bool true is this map contains no elements, false otherwise.
     */ 
    public function isEmpty();
    
    /**
     * Returns a set of keys contained in this map.
     *
     * @return SetInterface a set of keys contained in this map.
     */
    public function keys();
    
    /**
     * Returns a numeric array of values contained in this map.
     *
     * @return ListInterface a list of values contained in this map.
     */
    public function values();
}
