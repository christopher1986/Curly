<?php

namespace Curly\Lang\Filter;

use Traversable;

class JoinFilter
{
    /**
     * Returns a string representation of the specified collection, with the glue string between each element.
     *
     * @param array|Traversable $value a collection of elements to join.
     * @param string $glue (optional) the glue by which to concatenate the elements.
     * @return string a concatenated string, with the glue string between each element.
     */
    public function filter($value, $glue = '')
    {
        if ($value instanceof Traversable) {
            $value = iterator_to_array($value);
        }
        
        return (is_array($value)) ? join($glue, $value) : '';
    }
}
