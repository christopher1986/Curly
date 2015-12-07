<?php

namespace Curly\Ast\Node\Expression\Unary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractUnary;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The UnaryPlus node represents the unary plus operator and it's operand.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class UnaryPlus extends AbstractUnary
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $operands = $this->getChildren();
        $operand  = reset($operands);
        
        $value = $operand->render($context, $out);
        if (is_bool($value)) {
            $value = (int) $value;
        }
        
        if (!is_numeric($value)) {
            throw new TypeException(sprintf('%s is a bad operand type for unary +', gettype($value)), $operand->getLineNumber()); 
        }
        
        return +$value;
    }
}
