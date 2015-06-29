<?php

namespace Curly\Collection\Comparator;

class ObjectComparator implements Comparator
{
    /**
     * {@inheritDoc}
     */
    public function compare($firstObj, $secondObj)
    {
        if ($firstObj instanceof Comparable) {
            return $firstObj->compareTo($secondObj);
        }
        
        if (is_object($firstObj) && method_exists($firstObj, '__toString') && 
            is_object($secondObj) && method_exists($secondObj, '__toString')) {
            return strcmp((string) $firstObj, (string) $secondObj);
        }
        
        return strcmp(get_class($firstObj), get_class($secondObj));
    }
}
