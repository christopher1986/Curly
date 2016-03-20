<?php

namespace Curly\Lang\Filter;

/**
 * The EscapeFilter will escape all special characters to HTML entities.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class EscapeFilter
{    
    /**
     * Returns the specified value with all special characters converted to HTML entities.
     *
     * <code>
     *     $lower = 'Foo&Baz'|escape;
     * </code>
     *
     * @param string $value the string to escape.
     * @return string the specified string with HTML entities.
     * @link http://php.net/manual/en/function.htmlspecialchars.php htmlspecialchars
     */
    public function filter($value)
    {
        return (is_string($value)) ? htmlspecialchars($value, ENT_QUOTES) : $value;
    }
}
