<?php

namespace Curly\Ast\Node;

use SplFixedArray;

use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Ast\Expression\VariableNode;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ForNode extends Node
{   
    /**
     * The loop variables.
     *
     * @var SplFixedArray
     */
    private $loopVars;

    /**
     * A sequence of elements to iterate over.
     *
     * @var NodeInterface
     */
    private $sequence;
    
    /**
     * Construct a new Text node.
     *
     * @param array $loopVars a collection containing the loop variables.
     * @param NodeInterface a sequence of elements to iterate over.
     * @param array|Traversable $nodes (optional) a collection of nodes.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(array $loopVars, NodeInterface $sequence, $children = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($children, $lineNumber, $flags);
        $this->setVariables($loopVars);
        $this->setSequence($sequence);
    }
    
    /**
     * Set the loop variables.
     *
     * @param array|Traversable $loopVars a collection of loop variables.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    private function setVariables($loopVars)
    {
        if ($loopVars instanceof \Traversable) {
            $loopVars = iterator_to_array($loopVars);
        }
    
        if (!is_array($loopVars)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or instance of the Traversable; received "%s"',
                __METHOD__,
                (is_object($loopVars) ? get_class($loopVars) : gettype($loopVars))
            ));
        }

        $loopVars = array_filter($loopVars, array($this, 'isNode'));
        $this->loopVars = SplFixedArray::fromArray($loopVars, false);
    }
    
    /**
     * {@inheritDoc}
     */
    private function getVariables()
    {
        if ($this->loopVars === null) {
            $this->loopVars = new SplFixedArray();
        }
    
        return $this->loopVars;
    }
    
    /**
     * Set a node which represents the sequence of elements to iterate over.
     *
     * @param NodeInterface $sequence a sequence of elements.
     */
    private function setSequence(NodeInterface $sequence)
    {
        $this->sequence = $sequence;
    }
    
    /**
     * Returns the node which represents the sequence of elements to iterate over.
     *
     * @return NodeInterface a sequence of elements.
     */
    private function getSequence()
    {
        return $this->sequence;
    }
    
    /**
     * Returns true if the specified object is an {@link VariableNode} instance.
     *
     * @param mixed $obj the object to test.
     * @return bool true if the specified object is an {@link VariableNode} instance, false otherwise.
     */
    protected function isVariableNode($obj)
    {
        return (is_object($obj) && $obj instanceof VariableNode);
    }
}
