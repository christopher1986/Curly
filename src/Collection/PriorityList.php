<?php

namespace Curly\Collection;

use Curly\Common\ComparatorInterface;
use Curly\Util\Arrays;

/**
 * This class implements the {@see PriorityListInterface} and is backed by a native array.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class PriorityList extends ArrayList implements PriorityListInterface
{
    /**
     * A flag that when true indicates this list is not sorted.
     * 
     * @var bool
     */
    private $invalidate = false;

    /**
     * An object which imposes a total ordering on this collection.
     *
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * Construct a new PriorityList.
     *
     * @param array|Traversable $elements (optional) the collection whose elements to add to this priority list.
     * @param ComparatorInterface $comparator (optional) the comparator that will be used to order this priority list.
     */
    public function __construct($elements = null, $comparator = null)
    {
        parent::__construct($elements);
        $this->setComparator($comparator);
    }

    /**
     * {@inheritDoc}
     */
    public function add($element)
    {
        $added = parent::add($element);
        $this->invalidate = $added;
        
        return $added;
    }   
    
    /**
     * {@inheritDoc}
     */
    public function addAll($elements)
    {
        $added = parent::addAll($elements);
        $this->invalidate = $added;
        
        return $added;
    }
    
    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        parent::clear();
        $this->invalidate = true;
    }
    
    /**
     * {@inheritDoc}
     */
    public function remove($element)
    {
        $removed = parent::remove($element);
        $this->invalidate = $removed;
        
        return $removed;
    }
    
    /**
     * {@inheritDoc}
     */
    public function removeAll($elements)
    {
        $removed = parent::removeAll($elements);
        $this->invalidate = $removed;
        
        return $removed;
    }
    
    /**
     * {@inheritDoc}
     */
    public function removeByIndex($index)
    {
        $element = parent::removeByIndex($index);
        $this->invalidate = ($element !== null);

        return $element;
    }
    
    /**
     * {@inheritDoc}
     */
    public function retainAll($elements)
    {
        $changed = parent::retainAll($elements);
        $this->invalidate = $changed;
        
        return $changed;
    }
    
    /**
     * {@inheritDoc}
     */
    public function set($index, $element)
    {
        $oldElement = parent::set($index, $element);
        $this->invalidate = ($oldElement !== null);

        return $oldElement;
    }
    
    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        if ($this->invalidate) {
            $this->sort();
        }
        parent::rewind();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        if ($this->invalidate) {
            $this->sort();
        }
        return parent::getIterator();
    }
        
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        if ($this->invalidate) {
            $this->sort();
        }
        return $this->elements;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setComparator(ComparatorInterface $comparator = null)
    {
        $this->comparator = $comparator;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getComparator()
    {
        return $this->comparator;
    }
    
    /**
     * {@inheritDoc}
     */
    public function hasComparator()
    {
        return ($this->comparator !== null);
    }
    
    /**
     * Sort this list according to the provided comparator. if comparator is null, 
     * the natural ordering of the elements will be used..
     *
     * @return void
     */
    private function sort()
    {
        if ($this->hasComparator()) {
            Arrays::sort($this->elements, $this->getComparator());
        } else {
            sort($this->elements, SORT_NATURAL);
        }
        
        $this->invalidate = false;
    }
}
