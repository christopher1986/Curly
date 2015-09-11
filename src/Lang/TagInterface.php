<?php

namespace Curly\Lang;

use Curly\SubparserInterface;
use Curly\Common\ComparableInterface;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface TagInterface extends ComparableInterface, SubparserInterface
{
    /**
     * Returns the first word to match this tag.
     *
     * @return string a word which is associated with this tag.
     */
    public function getTag();

    /**
     * Returns a collection of one or more words which are reserved for this tag.
     * 
     * A tag can be separated into two different groups, namely simple and more complex tags.
     * Simple tags are associated with a single word whereas more complex tags may be associated 
     * with numerous words. By allowing a tag to reserve these words we ensure that these words 
     * cannot be used as variable names cannot which could otherwise lead to ambiguity.
     *
     * @return array a collection of words which are reserved.
     */
    public function getTags();
}
