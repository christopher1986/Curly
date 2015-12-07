<?php

namespace Curly\Lang\Operator\Binary;

use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\Binary\BinaryAssignment;
use Curly\Lang\Operator\AbstractBinaryOperator;

/** 
 * The AssignmentOperator creates a {@link AssignmentNode} which will assign the value of 
 * the specified right operand to the specified left operand.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class AssignmentOperator extends AbstractBinaryOperator
{
    /**
     * {@inheritDoc}
     */   
    public function getSymbol()
    {
        return '=';
    }
    
    /**
     * {@inheritDoc}
     */       
    public function getPrecedence()
    {
        return 5;
    }
    
    /**
     * Returns the operator associativity.
     *
     * @return string the associativity.
     */
    public function getAssociativity()
    {
        return self::RIGHT_ASSOCIATIVE;
    }
    
    /**
     * {@inheritDoc}
     */
    public function createNode(NodeInterface $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00)
    {
        return new BinaryAssignment($left, $right, $lineNumber, $flags);
    }
}
