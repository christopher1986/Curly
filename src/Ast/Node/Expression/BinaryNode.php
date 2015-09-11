<?php

namespace Curly\Ast\Node\Expression;

use Curly\Ast\Node;
use Curly\Ast\NodeInterface;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class BinaryNode extends Node
{
    /**
     * Construct a new Binary node.
     *
     * @param NodeInterface $left the left expression.
     * @param NodeInterface $right the right expression.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array($left, $right), $lineNumber, $flags);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        $children = parent::getChildren();
        $children->setSize(2);
        
        return $children;
    }
}
