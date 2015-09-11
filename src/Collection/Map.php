<?php

namespace Curly\Collection;

use ArrayAccess;
use ArrayIterator;

class Map implements MapInterface, ArrayAccess
{
    /**
     * A native array to hold the elements.
     *
     * @var array
     */
    private $items = array();

    /**
     * Construct a new Map.
     *
     * @param array|Traversable $items (optional) a collection of key-value pairs to add to this map.
     */
    public function __construct($items = null)
    {
        if ($items !== null) {
            $this->addAll($items);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function add($key, $value)
    {
        $oldValue = $this->get($key);        
        $this->items[$key] = $value;
        
        return $oldValue;
    }
    
    /**
     * {@inheritDoc}
     */
    public function addAll($items)
    {
        if ($items instanceof \Traversable) {
            $items = iterator_to_array($items);
        }
    
        if (!is_array($items)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($items) ? get_class($items) : gettype($items))
            ));
        }
        
        foreach ($items as $key => $value) {
            $this->items[$key] = $value;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $value = $default;
        if ($this->containsKey($key)) {
            $value = $this->items[$key];
        }
        
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key)
    {
        return (array_key_exists($key, $this->items));
    }
    
    /**
     * {@inheritDoc}
     */
    public function containsValue($value)
    {    
        return (in_array($value, $this->items, true));
    }
    
    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        $oldValue = $this->get($key);
        if ($this->containsKey($key)) {
            unset($this->items[$key]);
        }
        
        return $oldValue;
    }
    
    /**
     * {@inheritDoc}
     */
    public function replace($key, $value)
    {
        $oldValue = $this->get($key);
        if ($this->containsKey($key)) {
            $this->add($key, $value);
        }
        
        return $oldValue;
    }
    
    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->items = array();
    }
    
    /**
     * Returns the number of items contained by this map.
     *
     * @return int the number of items contained by this map.
     */
    public function count()
    {
        return (count($this->items));
    }
    
    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return ($this->count() === 0);
    }
    
    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        return new HashSet(array_keys($this->items));
    }
    
    /**
     * {@inheritDoc}
     */
    public function values()
    {
        return new ArrayList(array_values($this->items));
    }

    /**
     * Returns an external iterator over the items contained by this map.
     *
     * @return Iterator an external iterator.
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
    
    /**
     * Returns true if this collection contains the specified key. More formally returns true only if this collection
     * contains a key $k such that ($k === $key).
     *
     * @param mixed $key the key whose presence will be tested.
     * @return bool true if this collection contains the specified key, false otherwise.
     */
    public function offsetExists($key)
    {
        return $this->containsKey($key);
    }
    
    /**
     * Returns the value associated with the specified key, or null if this map contains no mapping for the specified key.
     *
     * @param mixed $key the key whose value will be returned.
     * @param mixed $default the value to return if no mapping exists for the specified key.
     * @return mixed the value associated with the specified, or null if no mapping exists for the key.
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }
    
    /**
     * Associates the specified value with the specified key in this map. Any previously associated value for the specified
     * key is replaced by the new value.
     *
     * @param mixed $key the key that will be mapped to the specified value.
     * @param mixed $value the value to associate with the specified key.
     */
    public function offsetSet($key, $value)
    {
        $this->add($key, $value);
    }
    
    /**
     * Removes if present the mapping for a key from this map. More formally removes a mapping from this map where
     * key $k will be equal to ($k === $key).
     *
     * @param mixed $key the key whose mapping will be removed.
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }  
}
