<?php

namespace Curly\Ast\Node\Expression\Binary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractBinaryNode;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The MultiplicationNode is responsible for rendering the multiplication operator and it's operands.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class MultiplicationNode extends AbstractBinaryNode
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        list($left, $right) = $this->getOperands($context, $out);
        return ($left * $right);
    }
}
