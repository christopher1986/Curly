<?php

namespace Curly\Ast\Node\Expression\Binary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractBinary;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The BinaryNotIn node represents an membership not in operator and it's operands.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class BinaryNotIn extends AbstractBinary
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        list($left, $right) = $this->getOperands($context, $out);
        
        if (is_string($right)) {
            return (strpos($right, $left) === false);
        }     
         
        if ($right instanceof \Traversable) {
            $right = iterator_to_array($right);
        }
        
        return !in_array($left, (array) $right);
    }
}
