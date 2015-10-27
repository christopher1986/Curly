<?php

namespace Curly\Lang;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface FilterInterface
{   
    /**
     * Apply this filter to the specified value.
     *
     * @param mixed $value the value to filter.
     * @param array|null $args (optional) additional argument for this filter.
     * @return mixed the filtered value.
     */
    public function filter($value, array $args = null);
}
