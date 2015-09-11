<?php

namespace Curly\Common\Comparator;

use Curly\Common\ComparatorInterface;

/**
 * A comparison function which allows the ordering on a collection of Comparable objects.
 * The ordering according to the {@link ComparableInterface::compareTo($obj)} method is 
 * also known as the class's natural ordering.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ObjectComparator implements ComparatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function compare($firstObj, $secondObj)
    {
        if ($firstObj instanceof ComparableInterface) {
            return $firstObj->compareTo($secondObj);
        }
        
        if (is_object($firstObj) && method_exists($firstObj, '__toString') && 
            is_object($secondObj) && method_exists($secondObj, '__toString')) {
            return strcmp((string) $firstObj, (string) $secondObj);
        }

        return strcmp(get_class($firstObj), get_class($secondObj));
    }
}
