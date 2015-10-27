<?php

namespace Curly\Collection\Stream;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Stream implements StreamInterface
{
    /**
     * A collection of elements on which to operate.
     *
     * @var array
     */
    private $elements = array();
    
    /**
     * The number of elements contained within the stream.
     *
     * @var int
     */
    private $size = 0;
    
    /**
     * An internal pointer.
     *
     * @var int
     */
    public $index = 0;

    /**
     * Construct a Stream.
     *
     * @param array $elements a collection of elements on which the stream will operate.
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
        $this->size     = count($elements);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return (isset($this->elements[$this->index])) ? $this->elements[$this->index] : null;
    }
     
    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return ($this->index < $this->size);
    }
    
    /**
     * {@inheritDoc}
     */
    public function consume()
    {
        $element = $this->current();
        $this->index++;
        
        return $element;
    }
    
    /**
     * {@inheritDoc}
     */
    public function skip($number = 1)
    {
        if (!is_numeric($number)) {
           throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));
        } else if ($number <= 0) {
           throw new \LogicException(sprintf(
                '%s: expects a positive number; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));  
        }
        
        $this->index += (int) $number;
        return $this->valid();
    }
    
    /**
     * {@inheritDoc}
     */
    public function peek($number = 1)
    {
        if (!is_numeric($number)) {
           throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));
        } else if ($number <= 0) {
           throw new \LogicException(sprintf(
                '%s: expects a positive number; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));  
        }
        
        $index = $this->index + (int) $number;
        return (isset($this->elements[$index])) ? $this->elements[$index] : null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function until($predicate, $consume = true)
    {
        $collection = array();
        $index      = $this->index;

        for ($index; $index < $this->size; $index++) {
            $collection[] = $this->elements[$index];
            if ($predicate($this->elements[$index])) {
                break;
            }
        }
        
        if ($consume) {
            $this->index = ++$index;
        }
        
        return new Stream($collection);
    }
    
    /**
     * {@inheritDoc}
     */
    public function filter($predicate)
    {
        $elements   = $this->toArray();
        $collection = array();

        foreach ($elements as $element) {
            if ($predicate($element)) {
                $collection[] = $element;
            }
        }
        
        return new self($collection);
    }
    
    /**
     * {@inheritDoc}
     */
    public function limit($size)
    {        
        if (is_numeric($number)) {
           throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));
        } else if ($number <= 0) {
           throw new \LogicException(sprintf(
                '%s: expects a positive number; received "%s"',
                __METHOD__,
                (is_object($number) ? get_class($number) : gettype($number))
            ));  
        }
    
        $length = ($size < $this->size) ? $size : null;
        $collection = array_slice($this->elements, 0, $length);
        
        return new Stream($collection);
    }
    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * Returns the number of elements contained within the stream.
     *
     * @return int the number of elements.
     */
    public function count()
    {
        return $this->size;
    }
}
