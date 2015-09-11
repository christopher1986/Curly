<?php

namespace Curly\Lang\Operator\Unary\Arithmetic;

use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\UnaryNode;
use Curly\Lang\Operator\AbstractUnaryOperator;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class PlusOperator extends AbstractUnaryOperator
{
    /**
     * {@inheritDoc}
     */   
    public function getSymbol()
    {
        return '+';
    }
    
    /**
     * {@inheritDoc}
     */       
    public function getPrecedence()
    {
        return 501;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createNode(NodeInterface $node, $lineNumber = -1, $flags = 0x00)
    {
        return new UnaryNode($node, $lineNumber, $flags);
    }
}

