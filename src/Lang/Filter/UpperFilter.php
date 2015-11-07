<?php

namespace Curly\Lang\Filter;

class UpperFilter
{
    public function filter($value)
    {
        return (is_string($value)) ? strtoupper($value) : '';
    }
}
