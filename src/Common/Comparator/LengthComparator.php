<?php

namespace Curly\Common\Comparator;

use Curly\Common\ComparatorInterface;

/**
 * A comparison function which allows the ordering on a collection of strings by their length.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class LengthComparator implements ComparatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function compare($s1, $s2)
    {
        if (is_string($s1) && is_string($s2)) {
            $len1 = strlen($s1); 
            $len2 = strlen($s2);
            
            if ($len1 == $len2) {
                return strcmp($s1, $s2);
            }
            return (($len1 - $len2) > 0) ? -1 : 1;
        }
        
        return 0;
    }
}
