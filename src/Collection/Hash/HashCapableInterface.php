<?php

namespace Curly\Collection\Hash;

interface HashCapableInterface
{
    /**
     * Returns unique identifier for this object.
     *
     * @return string a unique identifier.
     */
    public function getHashCode();
}
