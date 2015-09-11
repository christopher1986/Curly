<?php

namespace Curly\Collection;

use Curly\Common\ComparatorInterface;

/**
 * A collection (also known as a sequence) whose elements are ordered using a Comparator. If multiple elements are tied for the same 
 * order their position within the list is left unchanged. It's impossible to retrieve or insert elements using an index since the 
 * position of elements within a priority list are likely to change when new elements are added to the list.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface PriorityListInterface extends ListInterface
{
    /**
     * Set the compartor that will sort the element contained by this list.
     *
     * @param ComparatorInterface $comparator the comparator to sort the element contained by this list.
     */
    public function setComparator(ComparatorInterface $comparator = null);
    
    /**
     * Returns if present the comparator that sorts the elements within this list.
     *
     * @return Comparator the comparator, or null if this list is sorted to the natural ordering of it's elements.
     */
    public function getComparator();
    
    /**
     * Returns true if this list has a compartor.
     *
     * @return true if a comparator is present, false otherwise.
     */
    public function hasComparator();
}
