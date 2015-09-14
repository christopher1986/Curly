<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\AbstractNode;

class IfNode extends AbstractNode
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context)
    {
    
    }

    /**
     * Returns true if the specified object is a {@link ConditionalNode} instance.
     *
     * @param mixed $obj the object to test.
     * @return bool true if the specified object is a {@link ConditionalNode} instance, false otherwise.
     */
    protected function isNode($obj)
    {
        return (is_object($obj) && $obj instanceof ConditionalNode);
    }
}
