<?php

namespace Curly\Lang\Filter;

/**
 * The UpperFilter will uppercase all alphabetic characters.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class UpperFilter
{
    /**
     * Returns the specified value with all alphabetic characters converted to uppercase.
     *
     * <code>
     *     $lower = 'FooBarBaz'|upper;
     * </code>
     *
     * @param string $value the string to make to uppercase.
     * @return string the specified string in uppercase.
     * @link http://php.net/manual/en/function.strtoupper.php strtoupper
     */
    public function filter($value)
    {
        return (is_string($value)) ? strtoupper($value) : $value;
    }
}
