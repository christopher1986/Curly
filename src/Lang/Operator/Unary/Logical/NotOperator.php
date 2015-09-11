<?php

namespace Curly\Lang\Operator\Unary\Logical;

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
class NotOperator extends AbstractUnaryOperator
{
    /**
     * {@inheritDoc}
     */   
    public function getSymbol()
    {
        return 'not';
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
        return new UnaryNode($node, $lineNumber, $flags);
    }
}

