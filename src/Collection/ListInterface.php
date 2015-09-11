<?php

namespace Curly\Collection;

use Countable;
use IteratorAggregate;

/**
 * An ordered collection (also known as a sequence). Elements can be inserted to the list at a specified position. 
 * These elements can be retrieved from the list using a (positive integer) position.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface ListInterface extends Countable, IteratorAggregate
{
    /**
     * Add the specified element to this list.
     *
     * @param mixed $element the element to add to this list.
     * @return bool true if this list did not already contain the specified element.
     */
    public function add($element);
    
    /**
     * Add to this list all of the elements that are contained in the specified collection.
     *
     * @param array|\Traversable $elements collection containing elements to add to this list.
     * @return bool true if the list has changed, false otherwise.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    public function addAll($elements);
    
    /**
     * Removes all elements from this list. The list will be empty after this call returns.
     */
    public function clear();
    
    /**
     * Returns true if this list contains the specified element. More formally returns true only if this list
     * contains an element $e such that ($e === $element).
     *
     * @param mixed $element the element whose presence will be tested.
     * @return bool true if this list contains the specified element, false otherwise.
     */
    public function contains($element);
    
    /**
     * Returns true if this list contains all the elements contained by the specified collection.
     *
     * @param array|\Traversable $elements collection of elements whose presence will be tested.
     * @return bool true if this list contains all elements in the specified collection, false otherwise.
     */
    public function containsAll($elements);
    
    /**
     * Returns the element at the specified position in the list.
     *
     * @param int $index index of the element to return.
     * @return mixed the element at the specified position in this list.
     * @throws InvalidArgumentException if the given argument is not a numeric value.
     * @throws OutOfRangeException if the index is out of range ($index < 0 || $index >= ListInterface::count()).
     */
    public function get($index);
    
    /**
     * Returns the index of the first occurence of the specified element in this list, or -1 if this list does not 
     * contain the element.
     *
     * @param mixed $element to element to search for.
     * @return int the index of the first occurence of the specified element in this list, or -1 if no element was found.
     */
    public function indexOf($element);
    
    /**
     * Returns the index of the last occurence of the specified element in this list, or -1 if this list does not 
     * contain the element.
     *
     * @param mixed $element to element to search for.
     * @return int the index of the last occurence of the specified element in this list, or -1 if no element was found.
     */
    public function lastIndexOf($element);
    
    /**
     * Returns true if this list is considered to be empty.
     *
     * @return bool true is this list contains no elements, false otherwise.
     */
    public function isEmpty();
    
    /**
     * Removes if present the specified element from this list. More formally removes an element $e such 
     * that ($e === $element), if this list contains such an element.
     *
     * @param mixed $element the element to remove from this list.
     * @return boolean true if this list contained the specified element, false otherwise.
     */
    public function remove($element);
    
    /**
     * Removes from this list all of the elements that are contained in the specified collection.
     *
     * @param array|\Traversable $elements collection containing elements to remove from this list.
     * @return bool true if the list has changed, false otherwise.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    public function removeAll($elements);
    
    /**
     * Removes the specified element from this list if it is present. More formally removes an element $e
     * such that ($e === $element), if this list contains such an element.
     *
     * @param int index the index of the element to be removed.
     * @return mixed the element that was removed from this list, or null if no element was not found.
     * @throws InvalidArgumentException if the given argument is not a numeric value.
     * @throws OutOfRangeException if the index is out of range ($index < 0 || $index >= ListInterface::count()).
     */
    public function removeByIndex($index);
    
    /**
     * Retains only the elements in this list that are contained in the specified collection. In other words,
     * remove from this list all of it's elements that are not contained in the specified collection.
     *
     * @param array|\Traversable $elements collection containing element to be retained in this list.
     * @return bool true if the list has changed, false otherwise.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    public function retainAll($elements);
    
    /**
     * Insert the specified element at the specified position in this list. Elements after the specified position
     * will be shifted to the right to accommodate for the newly inserted element.
     *
     * @param int $index the position at which to insert the element.
     * @param mixed $element the element to be inserted.
     * @return mixed the element previously at the specified position.
     * @throws InvalidArgumentException if the given argument is not a numeric value.
     * @throws OutOfRangeException if the index is out of range ($index < 0 || $index >= ListInterface::count()).
     */
    public function set($index, $element);
    
    /**
     * Returns a new {@link ListInterface} object, which contains a portion of this list that lies between the 
     * specified $fromIndex, inclusive, and $toIndex, exclusive.
     *
     * @param int $fromIndex the index of the first element to include in the list.
     * @param int $toIndex the index of the last element which will not be included in the list.
     * @return List a list containing the elements between the specified two indexes.
     * @throws \InvalidArgumentException if either of the indexes are not positive numbers.
     * @throws \LogicException if $toIndex is larger than $fromIndex;
     * @throws \LogicException if $fromIndex is smaller than 0;
     * @throws \LogicException if $toIndex is larger than the number of elements contained by this list;
     */
    public function subList($fromIndex, $toIndex);
    
    /**
     * Returns an array containing all of the elements in this list.
     *
     * @return array an array containing all the elements in this list.
     */
    public function toArray();
}
