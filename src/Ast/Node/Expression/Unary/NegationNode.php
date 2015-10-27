<?php

namespace Curly\Ast\Node\Expression\Unary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractUnaryNode;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The NegationNode is responsible for rendering the logical-negation (logical-NOT) operator and it's operand.
 * 
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class NegationNode extends AbstractUnaryNode
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $boolean = boolval($this->getOperand($context, $out));
        return !$boolean;
    }
}
