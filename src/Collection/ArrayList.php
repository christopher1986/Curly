<?php

namespace Curly\Collection;

use Curly\Collection\Iterator\ListIterator;


/**
 * This class implements the {@see ListInterface} and is backed by a native array.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ArrayList implements ListInterface
{
    /**
     * A native array to hold the elements.
     *
     * @var array
     */
    protected $elements = array();
    
    /**
     * Construct a new ArrayList.
     *
     * @param array|Traversable $elements (optional) the collection whose elements to add to this list.
     */
    public function __construct($elements = null)
    {
        if ($elements !== null) {
            $this->addAll($elements);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function add($element)
    {
        $oldSize = $this->count();
        $this->elements[] = $element;
        
        return ($this->count() !== $oldSize);
    }
    
    /**
     * {@inheritDoc}
     */
    public function addAll($elements)
    {
        if ($elements instanceof \Traversable) {
            $elements = iterator_to_array($elements);
        }
    
        if (!is_array($elements)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($elements) ? get_class($elements) : gettype($elements))
            ));
        }
        
        $oldSize = $this->count();
        $this->elements = array_merge($this->elements, $elements);
        
        return ($this->count() !== $oldSize);
    }
    
    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->elements = array();
    }
    
    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
        return ($this->indexOf($element) !== -1);
    }
    
    /**
     * {@inheritDoc}
     */
    public function containsAll($elements)
    {
        if (!is_array($elements) && !($elements instanceof \Traversable)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($elements) ? get_class($elements) : gettype($elements))
            ));
        } 
    
        foreach ($elements as $element) {
            if (!$this->contains($element)) {
                return false;
            }           
        }
        return true;
    }
    
    /**
     * {@inheritDoc}
     */
    public function get($index)
    {
        if (!is_int($index)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an integer argument; received "%s"',
                __METHOD__,
                (is_object($index) ? get_class($index) : gettype($index))
            ));
        } else if ($index < 0 || $index >= $this->count()) {
            throw new \OutOfRangeException(sprintf(
                '%s: list size: %d; received index %s',
                __METHOD__, 
                $this->count(),
                $index
            ));
        }
        
        $element = null;
        if (isset($this->elements[$index])) {
            $element = $this->elements[$index];
        }
        return $element;
    }
    
    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        $index = array_search($element, $this->elements, true);
        if ($index === false) {
            $index = -1;
        }
        
        return $index;
    }
    
    /**
     * {@inheritDoc}
     */
    public function lastIndexOf($element)
    {
        $index = -1;
        if ($indices = array_keys($this->elements, $element, true)) {
            $index = end($indices); 
        }
        
        return $index;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return ($this->count() === 0);
    }
    
    /**
     * Returns the number of elements contained by this list.
     *
     * @return int the number of elements contained by this list.
     */
    public function count()
    {
        return (count($this->elements));
    }
    
    /**
     * {@inheritDoc}
     */
    public function remove($element)
    {
        $oldSize = $this->count();
        if (false !== ($index = array_search($element, $this->elements))) {
            $this->removeByIndex($index);
        }
        
        return ($this->count !== $oldSize);
    }
    
    /**
     * {@inheritDoc}
     */
    public function removeAll($elements)
    {
        if (!is_array($elements) && !($elements instanceof \Traversable)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($elements) ? get_class($elements) : gettype($elements))
            ));
        } 

        $modified = false;
        foreach ($elements as $element) {
            if (($index = $this->indexOf($element)) !== -1) {
                unset($this->elements[$index]);
                $modified = true;
            }
        }
        
        // if modified reset all numeric keys.
        if ($modified) {
            $this->elements = array_values($this->elements);
        }
        
        return $modified;
    }
    
    /**
     * {@inheritDoc}
     */
    public function removeByIndex($index)
    {
        if (!is_int($index)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an integer argument; received "%s"',
                __METHOD__,
                (is_object($fromIndex) ? get_class($fromIndex) : gettype($fromIndex))
            ));
        } else if ($index < 0 || $index >= $this->count()) {
            throw new \OutOfRangeException(sprintf(
                '%s: list size: %d; received index %s',
                __METHOD__, 
                $this->count(),
                $index
            ));
        }
        
        $element = null;
        if (isset($this->elements[$index])) {
            $element = $this->elements[$index];
            unset($this->elements[$index]);
                
            // reset all numeric keys.
            $this->elements = array_values($this->elements);
        }
        
        return $element;
    }
    
    /**
     * {@inheritDoc}
     */
    public function retainAll($elements)
    {
        if (!is_array($elements) && !($elements instanceof \Traversable)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($elements) ? get_class($elements) : gettype($elements))
            ));
        } 
                
        $tmp = array();
        foreach ($elements as $element) {
            if (($index = $this->indexOf($element)) !== -1) {
                $tmp[] = $this->elements[$index];
            }
        }
        
        $oldSize = $this->count();
        $this->elements = $tmp;
        
        return ($oldSize !== $this->count());
    }
    
    /**
     * {@inheritDoc}
     */
    public function set($index, $element)
    {
        if (!is_int($index)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an integer argument; received "%s"',
                __METHOD__,
                (is_object($index) ? get_class($index) : gettype($index))
            ));
        } else if ($index < 0 || $index >= $this->count()) {
            throw new \OutOfRangeException(sprintf(
                '%s: list size: %d; received index %s',
                __METHOD__, 
                $this->count(),
                $index
            ));
        }
        
        $oldElement = null;
        if (isset($this->elements[$index])) {
            $oldElement = $this->elements[$index];
        }
        
        $this->elements[$index] = $element;
        
        return $oldElement;
    }
    
    /**
     * Returns an external iterator over the elements in this list.
     *
     * @return ListIterator an iterator over the elements in this list.
     */    
    public function getIterator()
    {
        return new ListIterator($this);
    }
    
    /**
     * {@inheritDoc}
     */
    public function subList($fromIndex, $toIndex)
    {
        if (!is_int($fromIndex) && $fromIndex < 0) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a whole positive number; received "%s"',
                __METHOD__,
                $fromIndex
            ));
        } else if (!is_int($toIndex) && $toIndex < 0) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a whole positive number; received "%s"',
                __METHOD__,
                $toIndex
            ));
        } else if ($fromIndex > $toIndex) {
            throw new \LogicException(sprintf(
                '%s: $fromIndex (%s) > $toIndex (%s)',
                __METHOD__,
                $fromIndex,
                $toIndex
            ));
        } else if ($fromIndex < 0) {
            throw new \LogicException(sprintf(
                '%s: $fromIndex (%s) cannot be smaller than 0',
                __METHOD__,
                $fromIndex
            ));
        } else if ($toIndex > $this->count()) {
            throw new \LogicException(sprintf(
                '%s: $toIndex (%s) cannot be larger than %d',
                __METHOD__,
                $toIndex,
                $this->count()
            ));
        }
        
        $list = new static();
        if ($elements = array_slice($this->toArray(), $fromIndex, ($toIndex - $fromIndex))) {
            $list->addAll($elements);
        }
        return $list;
    }
    
    /**
     * {@inheritDoc}
     */
    public function filter($predicate)
    {
        $elements = array();
        foreach ($this as $element) {
            if ($predicate($element)) {
                $elements[] = $element;
            }
        }
        
        return new self($elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->elements;
    }
}
