<?php

namespace Curly\Collection;

use ArrayIterator;
use Curly\Hash\HashCapableInterface;

class HashSet implements SetInterface
{
    /**
     * A native array to hold the elements.
     *
     * @var array
     */
    private $elements = array();

    /**
     * Construct a new HashSet.
     *
     * @param array|Traversable $elements (optional) the collection whose elements to add to this set.
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
        $index = $this->computeHash($element);
        if (!isset($this->elements[$index])) {
            $this->elements[$index] = $element;
        }
        
        return ($this->count() !== $oldSize);
    }
    
    /**
     * {@inheritDoc}
     */
    public function addAll($elements)
    {    
        if (!is_array($elements) && !($elements instanceof \Traversable)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($elements) ? get_class($elements) : gettype($elements))
            ));
        }
        
        $oldSize = $this->count();
        foreach ($elements as $element) {
            $this->add($element);
        }
        
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
        $index = $this->computeHash($element);
        return (isset($this->elements[$index]));
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
        
        $index = $this->computeHash($element);
        if (isset($this->elements[$index])) {
            unset($this->elements[$index]);
        }
        
        return ($this->count() !== $oldSize);
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
            if ($this->remove($element)) {
                $modified = true;
            }
        }
        
        return $modified;
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
            $index = $this->computeHash($element);
            if (isset($this->elements[$index])) {
                $tmp[$index] = $this->elements[$index];
            }
        }
        
        $oldSize = $this->count();
        $this->elements = $tmp;
        
        return ($this->count() !== $oldSize);
    }

    /**
     * Returns an external iterator over the elements in this set.
     *
     * @return Traversable an iterator over the elements in this set.
     */    
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }
    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return array_values($this->elements);
    }

    /**
     * Computes the hash for the specified element.
     *
     * @param mixed $element the element whose hash to compute.
     * @return string the computed hash for the specified element, or '0' on failure.
     */
    private function computeHash($element)
    {
        if (is_object($element)) {
            if ($element instanceof HashCapableInterface) {
                return $element->getHashCode();
            }
            
            return sprintf('obj_%s', md5(serialize($element)));
        } else if (is_array($element)) {
            return sprintf('arr_%s', md5(serialize($element)));
        } else if (is_resource($element)) {
            return sprintf('res_%s', $element);
        } else if (is_scalar($element)) {
            return sprintf('str_%s', $element);
        }
        
        return '0';
    }
}
