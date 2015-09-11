<?php

namespace Curly\Collection;

class UnmodifiableMap implements UnmodifiableMapInterface
{
    /**
     * A map whose entries will be accessed.
     *
     * @var MapInterface
     */
    private $map;

    /**
     * Construct a new UnmodifiableMap.
     *
     * @param MapInterface $map the map which is made unmodifiable.
     */
    public function __construct(MapInterface $map)
    {
        $this->map = $map;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        return $this->map->get($key, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key)
    {
        return $this->map->containsKey($key);
    }
    
    /**
     * {@inheritDoc}
     */
    public function containsValue($value)
    {    
        return $this->map->containsValue($value);
    }
    
    /**
     * Returns the number of items contained by this map.
     *
     * @return int the number of items contained by this map.
     */
    public function count()
    {
        return $this->map->count();
    }
    
    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return $this->map->isEmpty();
    }
    
    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        return $this->map->keys();
    }
    
    /**
     * {@inheritDoc}
     */
    public function values()
    {
        return $this->map->values();
    }
    
    /**
     * Returns an external iterator over the items contained by this map.
     *
     * @return Iterator an external iterator.
     */
    public function getIterator()
    {
        return $this->map->getIterator();
    }
}
