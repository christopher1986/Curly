<?php

namespace Curly\Ast\Node\Expression\Unary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractUnary;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The UnaryNegation node represents the logical-negation (logical-NOT) operator and it's operand.
 * 
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class UnaryNegation extends AbstractUnary
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
