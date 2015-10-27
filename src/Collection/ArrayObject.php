<?php

namespace Curly\Collection;

use ArrayObject as SplArrayObject;

/**
 * This class inherits from the {@link http://php.net/manual/en/class.arrayobject.php ArrayObject} provided by 
 * the Standard PHP Library (SPL).
 *
 * This class extends the ArrayObject from the SPL extension by providing a {@see ArrayObject::__toString()} method 
 * which allows this object to be printed as a string to any output stream.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class ArrayObject extends SplArrayObject
{
    /**
     * Returns a string representation of this array object.
     *
     * @return string a string representation of this array object.
     */
    public function __toString()
    {
        $contents = array();
        foreach ($this as $key => $value) {
            if ($value === $this) {
                $contents[] = sprintf('%s: this Array', $key);
            } else if (is_scalar($value) || is_object($value) && method_exists($value, '__toString')) {
                $contents[] = sprintf('%s: %s', $key, (string) $value);
            } else if (is_object($value)) {
                $contents[] = sprintf('%s: %s (%s)', $key, get_class($value), spl_object_hash($value));
            } else if (is_resource($value)) {
                $contents[] = sprintf('%s: resource (%s)', $key, get_resource_type($value));
            } else if (is_null($value)) {
                $contents[] = sprintf('%s: NULL', $key);
            }
        }
        
        return sprintf('{%s}', implode(', ', $contents));
    }
}
