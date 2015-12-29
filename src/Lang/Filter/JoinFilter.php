<?php

namespace Curly\Lang\Filter;

/**
 * The JoinFilter will concatenate all elements of a collection with the specified argument.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class JoinFilter
{
    /**
     * Returns a string representation of the specified collection, with the glue string between each element.
     *
     * <code>
     *     $str = ['foo', 'bar', 'baz']|join('&');
     * </code>
     *
     * @param array|Traversable $value a collection of elements to join.
     * @param string $glue (optional) the glue by which to concatenate the elements.
     * @return string a concatenated string, with the glue string between each element.
     */
    public function filter($value, $glue = '')
    {
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        }
        
        return (is_array($value)) ? join($glue, $value) : '';
    }
}
