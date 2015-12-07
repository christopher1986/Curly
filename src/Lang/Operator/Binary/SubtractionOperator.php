<?php

namespace Curly\Lang\Operator\Binary;

use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\Binary\BinarySubtraction;
use Curly\Lang\Operator\AbstractBinaryOperator;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class SubtractionOperator extends AbstractBinaryOperator
{    
    /**
     * {@inheritDoc}
     */   
    public function getSymbol()
    {
        return '-';
    }
    
    /**
     * {@inheritDoc}
     */       
    public function getPrecedence()
    {
        return 30;
    }       

    /**
     * {@inheritDoc}
     */
    public function createNode(NodeInterface $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00)
    {
        return new BinarySubtraction($left, $right, $lineNumber, $flags);
    }
}
