<?php

namespace Curly\Collection;

use Countable;
use IteratorAggregate;

/**
 * A collection that contains no duplicates. The existence of an element is determined through an equality check.
 * More formally, for resources or scalar values this means that a pair of elements $e1 and $e2 will be equal if
 * ($e1 == $e2) returns true. Arrays are first casted into a string representation and are then compared through
 * the same check as a resource or scalar value. 
 *
 * Objects can follow one of the following two checks:
 *
 * 1) If the specified object implements the {@link HashCapableInterface} it's hash capable. For such objects
 *    the {@link HashCapableInterface::getHashCode()} method will be invoked. The hash code returned by this 
 *    method will be used to determine whether the specified object already exists within the set.
 * 2) In all other cases the {@link spl_object_hash($obj)} function will be used to obtain an unique identifier.
 *    This unique identifier is used to determine whether the specified object already exists within the set.
 *  
 * Care should be taken when relying on the {@link spl_object_hash($obj)} function to compare two objects for
 * equality. This function returns a unique identifier for an object. This means that the same identifier can
 * only be expected when two object are identical (they refer to the same memory address). If this behaviour 
 * is undesirable consider implementing the {@link HashCapableInterface} and ensure that two objects that are
 * considered equal return the same hash code.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface SetInterface extends Countable, IteratorAggregate
{
    /**
     * Add if not present the specified element to this set.
     *
     * @param mixed $element the element that is not present will be added to this collection.
     * @return bool true if this set did not already contain the specified element.
     */
    public function add($element);
    
    /**
     * Adds all of the elements in the specified collection to this set if they're not already present.
     *
     * @param array|\Traversable $elements collection containing elements to add to this set.
     * @return bool true if the set has changed, false otherwise.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    public function addAll($elements);
    
    /**
     * Removes all elements from this set. The set will be empty after this call returns.
     */
    public function clear();
    
    /**
     * Returns true if this set contains the specified element. More formally returns true only if this set
     * contains an element $e such that ($e === $element).
     *
     * @param mixed $element the element whose presence will be tested.
     * @return bool true if this set contains the specified element, false otherwise.
     */
    public function contains($element);
    
    /**
     * Returns true if this set contains all the elements contained by the specified collection.
     *
     * @param array|\Traversable $elements collection of elements whose presence will be tested.
     * @return bool true if this set contains all elements in the specified collection, false otherwise.
     */
    public function containsAll($elements);
    
    /**
     * Returns true if this set is considered to be empty.
     *
     * @return bool true is this set contains no elements, false otherwise.
     */
    public function isEmpty();
    
    /**
     * Removes if present the specified element from this set. More formally removes an element $e such 
     * that ($e === $element), if this set contains such an element.
     *
     * @param mixed $element the element to remove from this set.
     * @return bool true if this set contained the specified element, false otherwise.
     */
    public function remove($element);
    
    /**
     * Removes from this set all of the elements that are contained in the specified collection.
     *
     * @param array|\Traversable $elements collection containing elements to remove from this set.
     * @return bool true if the set has changed, false otherwise.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    public function removeAll($elements);
    
    /**
     * Retains only the elements in this set that are contained in the specified collection. In other words,
     * remove from this set all of it's elements that are not contained in the specified collection.
     *
     * @param array|\Traversable $elements collection containing element to be retained in this set.
     * @return bool true if the set has changed, false otherwise.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    public function retainAll($elements);
    
    /**
     * Returns an array containing all of the elements in this set.
     *
     * @return array an array containing all the elements in this set.
     */
    public function toArray();
}
