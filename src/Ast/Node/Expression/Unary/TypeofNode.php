<?php

namespace Curly\Ast\Node\Expression\Unary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractUnaryNode;
use Curly\Ast\Node\Expression\VariableNode;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The TypeofNode is responsible for rendering the typeof operator and it's operand.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class TypeofNode extends AbstractUnaryNode
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $default = 'undefined';
        $nodes   = $this->getChildren();
        if ($node = reset($nodes)) {
            // first check if variable exist.
            if ($node instanceof VariableNode && !$context->offsetExists($node->getName())) {
                return $default;
            }
            
            return (($typeof = gettype($node->render($context, $out))) !== 'unknown type') ? $typeof : $default;
        }
                
        return $default;
    }
}
