<?php

namespace Curly\Lang\Tag;

use Curly\Lang\TagInterface;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractTag implements TagInterface
{
    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        $tag  = '';
        $tags = $this->getTags();
        if (is_array($tags) && !empty($tags)) {
            $tag = reset($tags);
        }
        
        return $tag;
    }

    /**
     * {@inheritDoc}
     */
    public function compareTo($obj)
    {
        if ($obj instanceof self) {
            $tag1 = $this->getTag();
            $tag2 = $obj->getTag();
            
            if (is_string($tag1) && is_string($tag2)) {
                $len1 = strlen($tag1); 
                $len2 = strlen($tag2);
                
                if ($len1 == $len2) {
                    return strcmp($tag1, $tag2);
                }
                return (($len1 - $len2) > 0) ? -1 : 1;
            }
        }
        return 0;
    }
}
