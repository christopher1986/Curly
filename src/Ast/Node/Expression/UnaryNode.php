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
class UnaryNode extends Node
{
    /**
     * Construct a new Unary node.
     *
     * @param NodeInterface $expression an expression node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $expression, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array($expression), $lineNumber, $flags);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        $children = parent::getChildren();
        $children->setSize(1);
        
        return $children;
    }
}
