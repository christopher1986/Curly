<?php

namespace Curly\Lang\Operator\Binary\Membership;

use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\BinaryNode;
use Curly\Lang\Operator\AbstractBinaryOperator;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class InOperator extends AbstractBinaryOperator
{
    /**
     * {@inheritDoc}
     */   
    public function getSymbol()
    {
        return 'in';
    }
    
    /**
     * {@inheritDoc}
     */       
    public function getPrecedence()
    {
        return 20;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createNode(NodeInterface $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00)
    {
        return new BinaryNode($left, $right, $lineNumber, $flags);
    }
}

