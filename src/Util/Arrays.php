<?php

namespace Curly\Util;

use Curly\Common\ComparatorInterface;
use Curly\Common\EquatableInterface;

/**
 * This utility class contains array related operations.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Arrays
{
    /**
     * Returns true if the specified array contains the specified value.
     *
     * @param array $arr the array to be searched.
     * @param mixed $value the value to find.
     * @return bool true if the specified array contains the specified value, false otherwise.
     */
    public static function contains(array $arr, $value)
    {
        if ($value instanceof EquatableInterface) {
            foreach ($arr as $element) { 
                if ($value->equals($element)) {
                    return true;
                }
            }
            
            return false;
        }
        
        return in_array($arr, $value, true);
    }

    /**
     * Sorts the specified array according to the order imposed by the specified {@link Comparator}.
     *
     * @param array $arr the array to be sorted.
     * @param ComparatorInterface $comparator a comparator that will determine the order of the array.
     * @param bool $associative (optional) if true index association will be maintained.
     */
    public static function sort(array &$arr, ComparatorInterface $comparator, $associative = false)
    {    
        if ($associative) {
            uasort($arr, array($comparator, 'compare'));
        } else {
            usort($arr, array($comparator, 'compare'));
        }
    }
}
