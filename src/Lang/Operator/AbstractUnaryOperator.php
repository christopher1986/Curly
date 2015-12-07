<?php

namespace Curly\Lang\Operator;

use Curly\Ast\NodeInterface;
use Curly\Lang\OperatorInterface;

/**
 * A unary operator operators on a single expression, also known as an operand. The operator precedence determines
 * in which sequence a collection of operators are evaluated.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractUnaryOperator implements OperatorInterface
{
    /**
     * Returns a unary node for the specified expression node.
     *
     * @param NodeInterface $node the expression node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     * @return NodeInterface a unary node.
     */
    abstract public function createNode(NodeInterface $node, $lineNumber = -1, $flags = 0x00);
}
