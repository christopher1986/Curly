<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The Entry node is responsible for rendering key-value pairs of an associative array.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Entry extends Node
{
    /**
     * Construct a new Entry.
     *
     * @param NodeInterface $key the key expression.
     * @param NodeInterface $right the value expression.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $key, NodeInterface $value, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array($key, $value), $lineNumber, $flags);
    }
    
    /**
     * {@inheritDoc}
     *
     * An Entry requires two nodes. Providing more or less will raise an exception.
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
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $values = array();
        foreach ($this->getChildren() as $node) {
            $values[] = $node->render($context, $out);
        }
        
        return $values;
    }
}
