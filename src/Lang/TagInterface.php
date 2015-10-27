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
     * Returns the name by which this tag is identified.
     *
     * @return string the name that identifies this tag.
     */
    public function getName();
}
