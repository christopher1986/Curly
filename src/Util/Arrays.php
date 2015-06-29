<?php

namespace Curly\Util;

use Curly\Collection\Comparator\Comparator;

/**
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Arrays
{
    /**
     * Sorts the specified array according to the order imposed by the specified {@link Comparator}.
     *
     * @param array $arr the array to be sorted.
     * @param Comparator $comparator a comparator that will determine the order of the array.
     * @param bool $associative (optional) if true index association will be maintained.
     */
    public static function sort(array &$arr, Comparator $comparator, $associative = false)
    {    
        if ($associative) {
            uasort($arr, array($comparator, 'compare'));
        } else {
            usort($arr, array($comparator, 'compare'));
        }
    }
}
