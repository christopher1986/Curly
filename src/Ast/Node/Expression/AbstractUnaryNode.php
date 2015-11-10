<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The {@link AbstractUnaryNode} provides a skeleton implementation for all unary nodes.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractUnaryNode extends Node
{
    /**
     * Construct a new Unary node.
     *
     * @param NodeInterface $expression an expression node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $expression, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array($expression), $lineNumber, $flags);
    }
    
    /**
     * {@inheritDoc}
     *
     * A binary node requires a single node. Providing more or less will raise an exception.
     *
     * @throws LogicException if the specified collection contains more or less than one node.
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
        } else if (($count = count($nodes)) != 1) {
            throw new \LogicException(sprintf(
                '%s: expects exactly 1 child node; received "%d" node(s)',
                __METHOD__,
                $count
            ));
        }

        parent::setChildren($nodes);
    }
    
    /**
     * Returns an operand which has been rendered using the specified context.
     *
     * @param ContextInterface $context the template context.
     * @param OutputStreamInterface $out the output stream.
     * @return mixed a rendered operand.
     */
    protected function getOperand(ContextInterface $context, OutputStreamInterface $out)
    {
        $nodes = $this->getChildren();
        return $nodes[0]->render($context, $out);
    }
}
