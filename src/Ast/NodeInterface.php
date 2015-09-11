<?php

namespace Curly\Ast;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface NodeInterface
{    
    /**
     * Returns if set the flags for this node.
     *
     * A flag is either a bitmask, or named constant. A bitwise operator can be used
     * to check check if a flag is present (($flags & $flag) === $flag).
     *
     * @return int a bitmask for the flags set.
     */
    public function getFlags();
    
    /**
     * Returns true if the specified flags are set for this node.
     *
     * @param int $flags one or more flags whose presence will be tested.
     * @return bool true if the specified flags are set for this node, false otherwise.
     */
    public function hasFlags($flags);
    
    /**
     * Returns if present the line number. If no line number was provided
     * a negative number is returned.
     * 
     * @return int the line number, defaults to -1.
     */
    public function getLineNumber();
    
    /**
     * Returns a collection of child nodes.
     *
     * @return Traversable a collection of nodes.
     */
    public function getChildren();
}
