<?php

namespace Curly\Lang\Filter;

/**
 * The LowerFilter will lowercase all alphabetic characters.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class LowerFilter
{    
    /**
     * Returns the specified value with all alphabetic characters converted to lowercase.
     *
     * <code>
     *     $lower = 'FooBarBaz'|lower;
     * </code>
     *
     * @param string $value the string to make to lowercase.
     * @return string the specified string in lowercase.
     * @link http://php.net/manual/en/function.strtolower.php strtolower
     */
    public function filter($value)
    {
        return (is_string($value)) ? strtolower($value) : $value;
    }
}
