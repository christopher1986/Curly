<?php

namespace Curly;

use ArrayIterator;
use IteratorAggregate;
use SplDoublyLinkedList;

use Webwijs\Collection\Exception\EmptyCollectionException;

/**
 * The TemplateContext is a concrete implementation of the {@link ContextInterface} interface and gives
 * a template the means to store or retrieve variables. More complex control structures such as 
 * conditional statements and loop statements can push and pop their own {@link ContextInterface}
 * instance onto the TemplateContext.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class TemplateContext implements ContextInterface, IteratorAggregate
{
    /**
     * A stack of ContextInterface instances. 
     *
     * @var SplStack
     */
    private $contexts = null;

    /**
     * A collection containing key-value pairs for this context.
     *
     * @var array
     */
    private $items = array();

    /**
     * Construct a new TemplateContext.
     *
     * @param array $items (optional) a collection of items.
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
        $this->contexts = new SPLDoublyLinkedList();
        $this->contexts->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);
    }

    /**
     * {@inheritDoc}
     */
    public function push(ContextInterface $context)
    {
        $this->contexts->push($context);
    }
    
    /**
     * {@inheritDoc}
     */
    public function pop()
    {
        if ($this->contexts->isEmpty()) {
            throw new EmptyCollectionException(sprintf(
                '%s: unable to remove context from empty stack.',
                __METHOD__
            ));
        }
        
        return $this->contexts->pop();
    }
    
    /**
     * {@inheritDoc}
     */
    public function poll()
    {
        return (!$this->contexts->isEmpty()) ? $this->pop() : null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function flatten()
    {
        // change iterator mode from LIFO to FIFO.
        $newMode  = SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP;
        $prevMode = $this->contexts->getIteratorMode();
        
        $this->contexts->setIteratorMode($newMode);
        
        $flattened = $this->items;
        foreach ($this->contexts as $context) {
            $flattened = array_merge($flattened, $context->flatten());
        }
        
        $this->contexts->setIteratorMode($prevMode);
        
        return $flattened;
    }
    
    /**
     * Indicates whether this context is considered equal to the specified context.
     *
     * @param mixed $context the context for which equality should be tested.
     * @return bool true if this context is equal to the specified context, false otherwise.
     */
    public function equals($context)
    {
        if ($context instanceof ContextInterface) {
            return ($this->flatten() == $context->flatten());
        }
        
        return false;
    }
    
    /**
     * Tests whether the specified offset exists.
     *
     * @param mixed $offset the offset whose presence will be tested.
     * @return bool true if the specified offset exists, otherwise false.
     */    
    public function offsetExists($offset)
    {
        if (array_key_exists($offset, $this->items)) {
            return true;
        }
    
        foreach ($this->contexts as $context) {
            if ($context->offsetExists($offset)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Returns the value assigned to the specified offset.
     *
     * @param mixed $offset the offset whose value will be returned.
     * @return mixed|null the value assigned to the specified offset, or null on failure.
     */
    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->items)) {
            return $this->items[$offset];
        }
        
        // change iterator mode from LIFO to FIFO.
        $newMode  = SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP;
        $prevMode = $this->contexts->getIteratorMode();
        
        $this->contexts->setIteratorMode($newMode);
        
        $value = null;
        foreach ($this->contexts as $context) {
            if ($context->offsetExists($offset)) {
                $value = $context[$offset];
                break;
            }
        }
        
        $this->contexts->setIteratorMode($prevMode);
        
        return $value;
    }
    
    /**
     * Assign a value to the specified offset. 
     *
     * @param mixed $offset the offset to which a value will be assigned.
     * @param mixed $value the value to set.
     */
    public function offsetSet($offset, $value)
    {
        if (array_key_exists($offset, $this->items)) {
            $this->items[$offset] = $value;
            return;
        }
        
        $context = $this;
        foreach ($this->contexts as $context) {
            if ($context->offsetExists($offset)) {
                $context[$offset] = $value;
                return;
            }
        }

        if ($offset === null) {
            $context->items[] = $value;
        } else {
            $context->items[$offset] = $value;
        }
    }
    
    /**
     * Remove the specified offset and the assigned value.
     *
     * @param mixed $offset the offset to remove.
     */
    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->items)) {
            unset($this->items[$offset]);
            return;
        }
    
        foreach ($this->contexts as $context) {
            if ($context->offsetExists($offset)) {
                unset($context[$offset]);
                return;
            }
        }
    }
    
    /**
     * Returns an external iterator for this (flattened) context.
     *
     * @see ContextInterface::flatten()
     */
    public function getIterator()
    {
        return new ArrayIterator($this->flatten());
    }
}
