<?php

namespace Curly\Ast\Node\Expression\Unary;

use Curly\Ast\AbstractNode;
use Curly\Ast\NodeInterface;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractUnaryNode extends AbstractNode
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
