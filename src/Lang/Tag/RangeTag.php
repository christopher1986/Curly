<?php

namespace Curly\Lang\Tag;

use Curly\Collection\ArrayObject;

class RangeTag extends AbstractTag
{
    /**
     * Returns an array containing a range of elements.
     *
     * <code>
     *     $array = range(0, 100, 10);
     * </code>
     *
     * @param mixed $start the first value of the sequence.
     * @param mixed $end the sequence is ended upon reaching the end value.
     * @param int $step (optional) the incremental value used between the steps.
     * @return ArrayObject an array for the specified range.
     * @link http://php.net/manual/en/function.range.php range
     */
    public function call($start, $end, $step = 1)
    {
        $array = range($start, $end, $step);
        return new ArrayObject($array);
    }
}
