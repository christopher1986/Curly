<?php

namespace Curly\Lang\Operator;

use Curly\Ast\NodeInterface;
use Curly\Lang\OperatorInterface;

/**
 * A binary operator combines two expressions, also known as operands to produce another expression.
 * The operator precedence determines in which sequence a collection of operators are evaluated. 
 * The associativity conrols the order in which operations are performed when an operand occurs between 
 * two operators with the same precedence.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractBinaryOperator implements OperatorInterface
{
    /**
     * A left to right associativity.
     *
     * @var int
     */
    const LEFT_ASSOCIATIVE = 0x01;
    
    /**
     * A right to left associativity.
     *
     * @var int
     */
    const RIGHT_ASSOCIATIVE = 0x02;
    
    /**
     * Returns the operator associativity.
     *
     * @return string the associativity.
     */
    public function getAssociativity()
    {
        return self::LEFT_ASSOCIATIVE;
    }
    
    /**
     * Returns true if this operator is right associative.
     *
     * @return bool true if this operator is right associative, false otherwise.
     */
    public function isRightAssociative()
    {
        return ($this->getAssociativity() === self::RIGHT_ASSOCIATIVE);
    }
    
    /**
     * Returns true if this operator is left associative.
     *
     * @return bool true if this operator is left associative, false otherwise.
     */
    public function isLeftAssociative()
    {
        return ($this->getAssociativity() === self::LEFT_ASSOCIATIVE);
    }
    
    /**
     * Returns a binary node for the specified expression nodes.
     *
     * @param NodeInterface $left the left expression node.
     * @param NodeInterface $right the right expression node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     * @return NodeInterface a binary node
     */
    abstract public function createNode(NodeInterface $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00);
}
