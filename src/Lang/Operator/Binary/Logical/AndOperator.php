<?php

namespace Curly\Lang\Operator\Binary\Logical;

use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\UnaryNode;
use Curly\Lang\Operator\AbstractBinaryOperator;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class NotOperator extends AbstractBinaryOperator
{
    /**
     * {@inheritDoc}
     */   
    public function getSymbol()
    {
        return 'and';
    }
    
    /**
     * {@inheritDoc}
     */       
    public function getPrecedence()
    {
        return 15;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createNode(NodeInterface $node, $lineNumber = -1, $flags = 0x00)
    {
        return new UnaryNode($node, $lineNumber, $flags);
    }
}

