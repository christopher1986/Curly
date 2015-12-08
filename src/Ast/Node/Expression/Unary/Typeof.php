<?php

namespace Curly\Ast\Node\Expression\Unary;

use ArrayAccess;
use Traversable;

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
        $default = 'unknown';
        $nodes = $this->getChildren();
        if ($node = reset($nodes)) {
            // non-existing variable.
            if ($node instanceof Variable && !$context->offsetExists($node->getIdentifier())) {
                return $default;
            }
            
            $type = gettype($node->render($context, $out));
            if ($type === 'object') {
                if ($type instanceof ArrayAccess && $type instanceof Traversable) {
                    $type = 'array';
                } else if ($type instanceof ArrayAccess) {
                    $type = 'arrayobject';
                } else if ($type instanceof Traversable) {
                    $type = 'traversable';
                }
            } else if ($type === 'unknown type') {
                $type = $default;
            }
            
            return $type;
        }
                
        return $default;
    }
}
