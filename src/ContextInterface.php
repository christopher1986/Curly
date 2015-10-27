<?php

namespace Curly;

use ArrayAccess;

use Curly\Common\EquatableInterface;

/**
 * The ContextInterface stores and provides access to variables. A context itself can consist 
 * zero or more other {@link ContextInterface} instances. These context objects are stored in 
 * a last-in-first-out (LIFO) stack.
 *
 * The behaviour of a context makes it similar to associative arrays, dictionaries and maps in 
 * other programming languages. Each variable is stored as a key-value pair which allows you
 * to access the variable using the mapped key, also known as the variable name.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface ContextInterface extends ArrayAccess, EquatableInterface
{
    /**
     * Push a new context onto the top of this {@link ContextInterface} instance.
     *
     * @param ContextInterface $context the context to push onto the stack.
     */
    public function push(ContextInterface $context);
    
    /**
     * Pop the context at the top of this {@link ContextInterface} instance.
     *
     * @return ContextInterface the context that was removed.
     * @throws EmptyCollectionException if the underlying collection containing context instances is empty.
     */
    public function pop();
    
    /**
     * Retrieves and removes the the context at the top of this {@link ContextInterface} instance.
     *
     * Unlike the {@link ContextInterface::pop()} this method returns a NULL literal instead
     * of throwing a {@link EmptyCollectionException} exception.
     *
     * @return ContextInterface|null the context that was removed, or null on failure.
     */
    public function poll();
    
    /**
     * Flatten all contexts into a single {@link ContextInterface} instance.
     *
     * @return array a collection of variables of all {@link ContextInterface} instances.
     */
    public function flatten();
}
