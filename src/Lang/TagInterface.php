<?php

namespace Curly\Lang;

use Curly\SubparserInterface;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface TagInterface extends SubparserInterface
{
    /**
     * Returns a word by this tag is identified.
     *
     * @return string a word which that identifies this tag.
     */
    public function getTag();
}
