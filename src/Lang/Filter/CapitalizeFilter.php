<?php

namespace Curly\Lang\Filter;

/**
 * The CapitalizeFilter will uppercase the first character of a string.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class CapitalizeFilter
{    
    /**
     * Returns the specified value whose first letter character has been made uppercase.
     *
     * <code>
     *     $lower = 'foo bar baz'|capitalize;
     * </code>
     *
     * @param string $value the string whose first character to make uppercase.
     * @return string the resulting string.
     * @link http://nl3.php.net/manual/en/function.ucfirst.php ucfirst
     */
    public function filter($value)
    {
        return (is_string($value)) ? ucfirst($value) : $value;
    }
}
