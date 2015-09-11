<?php

namespace Curly\Collection;

use Countable;
use IteratorAggregate;

interface MapInterface extends Countable, IteratorAggregate
{
    /**
     * Associates the specified value with the specified key in this map. Any previously associated value for the specified
     * key is replaced by the new value.
     *
     * @param string|int $key the key that will be mapped to the specified value.
     * @param mixed $value the value to associate with the specified key.
     * @return mixed the previously associated value with the key, or null if there was no mapping.
     */
    public function add($key, $value);
    
    /**
     * Add a collection containing key-value pairs into this map.
     *
     * @param array|Traversable $items a collection containing key-value pairs.
     * @throws InvalidArgumentException if the specified argument is not an array or Traversable object.
     */
    public function addAll($items);
    
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
     * Removes if present the mapping for a key from this map. More formally removes a mapping from this map where
     * key $k will be equal to ($k === $key).
     *
     * @param mixed $key the key whose mapping will be removed.
     * @return mixed the value that was previously associated with the mapping, or null if no mapping exists for the key.
     */
    public function remove($key);
    
    /**
     * Replaces if present the value associated with the specified key.
     *
     * @param mixed $key the key whose value will be replaced.
     * @param mixed $value the value to associate with the specified key.
     * @return mixed the value that was previously associated with the specified key, or null if there was no mapping for the key.
     */
    public function replace($key, $value);
    
    /**
     * Removes all of the mappings from this map. The map will be empty after this call returns.
     */
    public function clear();
    
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
