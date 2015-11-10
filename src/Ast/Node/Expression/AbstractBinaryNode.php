<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The {@link AbstractBinaryNode} provides a skeleton implementation for all binary nodes.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractBinaryNode extends Node
{
    /**
     * Construct a new Binary node.
     *
     * @param NodeInterface $left the left expression.
     * @param NodeInterface $right the right expression.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array($left, $right), $lineNumber, $flags);
    }
    
    /**
     * {@inheritDoc}
     *
     * A binary node requires two nodes. Providing more or less will raise an exception.
     *
     * @throws LogicException if the specified collection contains more or less than two nodes.
     */
    public function setChildren($nodes)
    {
        if ($nodes instanceof \Traversable) {
            $nodes = iterator_to_array($nodes);
        }
    
        if (!is_array($nodes)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or instance of the Traversable; received "%s"',
                __METHOD__,
                (is_object($nodes) ? get_class($nodes) : gettype($nodes))
            ));
        } else if (($count = count($nodes)) != 2) {
            throw new \LogicException(sprintf(
                '%s: expects exactly 2 child nodes; received "%d" node(s)',
                __METHOD__,
                $count
            ));
        }

        parent::setChildren($nodes);
    }
    
    /**
     * Returns a collection of operands which has been rendered using the specified context.
     *
     * @param ContextInterface $context the template context.
     * @param OutputStreamInterface $out the output stream.
     * @return array a collection of rendered operands.
     */
    protected function getOperands(ContextInterface $context, OutputStreamInterface $out)
    {
        $operands = array();
        foreach ($this->getChildren() as $child) {
            $operands[] = $child->render($context, $out);
        }
        
        return $operands;
    }
}
