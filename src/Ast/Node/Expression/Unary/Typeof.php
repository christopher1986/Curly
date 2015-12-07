<?php

namespace Curly\Ast\Node\Expression\Unary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractUnary;
use Curly\Ast\Node\Expression\Variable;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The Typeof node represents typeof operator and it's operand.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Typeof extends AbstractUnary
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $default = 'undefined';
        $nodes = $this->getChildren();
        if ($node = reset($nodes)) {
            // check if variable exists.
            if ($node instanceof Variable && !$context->offsetExists($node->getIdentifier())) {
                return $default;
            }
            
            return (($typeof = gettype($node->render($context, $out))) !== 'unknown type') ? $typeof : $default;
        }
                
        return $default;
    }
}
