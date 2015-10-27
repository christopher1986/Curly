<?php

namespace Curly\Ast\Node\Expression\Binary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractBinaryNode;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The GreaterEqualNode is responsible for rendering the relational greater than or equal to operator and it's operands.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class GreaterEqualNode extends AbstractBinaryNode
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        list($left, $right) = $this->getOperands($context, $out);
        return ($left >= $right);
    }
}
