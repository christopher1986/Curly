<?php

namespace Curly\Lang\Operator\Unary;

use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\Unary\UnaryNegation;
use Curly\Lang\Operator\AbstractUnaryOperator;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class NegationOperator extends AbstractUnaryOperator
{    
    /**
     * {@inheritDoc}
     */   
    public function getSymbol()
    {
        return '!';
    }
    
    /**
     * {@inheritDoc}
     */       
    public function getPrecedence()
    {
        return 500;
    }

    /**
     * {@inheritDoc}
     */
    public function createNode(NodeInterface $node, $lineNumber = -1, $flags = 0x00)
    {
        return new UnaryNegation($node, $lineNumber, $flags);
    }
}

