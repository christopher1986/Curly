<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The ConditionalNode will render one or more expression nodes into a boolean result by evaluating the expression.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class ConditionalNode extends Node
{
    /**
     * A node which resembles the expression which will be evaluated.
     *
     * @var NodeInterface
     */
    private $condition = null;

    /**
     * Construct a new ConditionalNode.
     *
     * @param NodeInterface|null $condition (optional) the expression to evaluate, or null which evaluates to true.
     * @param array|Traversable $nodes (optional) a collection of nodes.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $condition = null, $children = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($children, $lineNumber, $flags);
        $this->setCondition($condition);
    }

    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        foreach ($this->getChildren() as $node) {
            $node->render($context, $out);
        }
    }
    
    /**
     * Returns true if the underlying condition of this node is satisfied.
     *
     * A {@link ConditionalNode} which lacks a condition will always evaluate to true.
     * These conditional nodes are always satisfied, and it usually implies that the
     * node was created to satisy an else statement.
     *
     * @param ContextInterface $context the template context with which to render a node.
     * @param OutputStreamInterface $out the output stream.
     * @return bool true if the condition is satisfied, false otherwise.
     */
    public function isTrue(ContextInterface $context, OutputStreamInterface $out)
    {
        $condition = $this->getCondition();
        $satisfied = ($condition === null);
        if ($condition instanceof NodeInterface) {
            $satisfied = boolval($condition->render($context, $out));
        }
        
        return $satisfied;
    }
    
    /**
     * Set the expression to evaluate, or null which is equivelant to an expression which evaluates to true.
     *
     * @param NodeInterface $node (optional) the expression to evaluate, or null which evaluates to true.
     */
    public function setCondition(NodeInterface $node = null)
    {
        $this->condition = $node;
    }
    
    /**
     * Returns the expression to evaluate, or null which is equivelant to an expression which evaluates to true.
     *
     * @return NodeInterface|null the expression to evaluate, or null.
     */
    private function getCondition()
    {
        return $this->condition;
    }
}
