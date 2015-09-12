<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\AbstractNode;
use Curly\Ast\NodeInterface;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class EntryNode extends AbstractNode
{
    /**
     * Construct a new Entry node.
     *
     * @param NodeInterface $key the key expression.
     * @param NodeInterface $right the value expression.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $key, NodeInterface $value, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array($key, $value), $lineNumber, $flags);
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
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context)
    {
    
    }
}
