<?php

namespace Curly\Ast\Node\Expression\Unary;

use Curly\ContextInterface;
use Curly\Ast\Node\Expression\AbstractUnary;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The UnaryMinus node represents the arithmetic-negation operator and it's operand.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class UnaryMinus extends AbstractUnary
{
    /**
     * {@inheritDoc}
     *
     * @throws TypeException if the operand can not be negated.
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
            throw new TypeException(sprintf('%s is a bad operand type for unary -', gettype($value)), $operand->getLineNumber()); 
        }
        
        return -$value;
    }
}
