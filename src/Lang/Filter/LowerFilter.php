<?php

namespace Curly\Lang\Filter;

use Curly\Lang\FilterInterface;

class LowerFilter implements FilterInterface
{    
    /**
     * Returns the specified value with all alphabetic characters converted to lowercase.
     *
     * @param string $value the string to make to lowercase.
     * @param array|null $args (optional) additional arguments used by this filter.
     * @return string the lowercased string.
     */
    public function filter($value, array $args = null)
    {
        var_dump('works');
    }
}
