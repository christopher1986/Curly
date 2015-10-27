<?php

namespace Curly\Loader;

/**
 * The StringLoader accepts nearly any string except for those that represent
 * an existing file or directory.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class StringLoader extends AbstractLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($input)
    {
        if (!is_string($input)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($input) ? get_class($input) : gettype($input))
            ));
        }
        
        // file or directory paths are not parsable.
        if (file_exists($input)) {
            $content = ($this->next !== null) ? $this->next->load($input) : null;
        } else {
            $content = $input;
        }
        
        return $content;
    }
}
