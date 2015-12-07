<?php

namespace Curly\Lang\Filter;

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
