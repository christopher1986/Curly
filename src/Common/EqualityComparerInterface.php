<?php

namespace Curly\Common;

/**
 * The EqualityComparerInterface will compare two values for equality.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface EqualityComparerInterface
{
    /**
     * Determines whether the specified arguments are equal.
     *
     * @param mixed $firstValue the value to be compared.
     * @param mixed $secondValue the value to compare with.
     * @return bool true if the specified values are considered equal, otherwise false.         
     */
    public function equals($firstValue, $secondValue);
}
