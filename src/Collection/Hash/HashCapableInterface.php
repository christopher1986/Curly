<?php

namespace Curly\Collection\Hash;

/**
 * The HashCapableInterface should be implementated by objects that are capable of implementing computing an hash code.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface HashCapableInterface
{
    /**
     * Returns unique identifier for this object.
     *
     * @return string a unique identifier.
     */
    public function getHashCode();
}
