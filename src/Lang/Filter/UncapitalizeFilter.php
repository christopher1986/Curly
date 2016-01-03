<?php

namespace Curly\Lang\Filter;

/**
 * The UncapitalizeFilter will lowercase the first character of a string.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class UncapitalizeFilter
{    
    /**
     * Returns the specified value whose first letter character has been made lowercase.
     *
     * <code>
     *     $lower = 'foo bar baz'|capitalize;
     * </code>
     *
     * @param string $value the string whose first character to make lowercase.
     * @return string the resulting string.
     * @link http://nl3.php.net/manual/en/function.lcfirst.php lcfirst
     */
    public function filter($value)
    {
        return (is_string($value)) ? lcfirst($value) : $value;
    }
}
