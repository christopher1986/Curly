<?php

namespace Curly\Ast;

use Curly\ContextInterface;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Visitor\VisitorInterface;

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
     * Silently ignore non-existing property.
     *
     * @var int
     */
    const E_NONE = 0x01;

    /**
     * Display errors for non-existing property.
     *
     * @var int
     */
    const E_STRICT = 0x02;
  
    /**
     * Set the specified flags for this node.
     *
     * @param int $flags (optional) a bitmask for one or more flags.
     * @throws InvalidArgumentException if the given argument is not an integer value.
     */
    public function setFlags($flags = 0x00);
  
    /**
     * Add the specified flags for this node.
     *
     * @param int $flags a bitmask for one or more flags.
     * @throws InvalidArgumentException if the given argument is not an integer value.
     */
    public function addFlags($flags);
  
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
     * Removes if present the specified flag.
     *
     * @param int $flags a bitmask for one or more flags.
     */
    public function removeFlags($flags);

    /**
     * Clear all flags for this node.
     */
    public function clearFlags();
    
    /**
     * Returns if present the line number, or -1 if no line number was provided.
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
    
    /**
     * Allow a visitor to vitor this node.
     *
     * @param VisitorInterface $visitor the visitor.
     */
    public function accept(VisitorInterface $visitor);
    
    /**
     * Render this node with the specified context.
     *
     * @param ContextInterface $context the template context.
     * @param OutputStreamInterface $out the output stream.
     */
    public function render(ContextInterface $context, OutputStreamInterface $out);
}
