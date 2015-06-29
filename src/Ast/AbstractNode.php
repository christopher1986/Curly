<?php

namespace Curly\Ast;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * A bitmask of flags.
     *
     * @var int
     */
    private $flags = 0x00;

    /**
     * A line number.
     *
     * var int
     */
    private $lineNumber = -1;

    /**
     * A collection of nodes.
     *
     * @var array
     */
    private $children = array();
    
    /**
     * Set the specified flags for this node.
     *
     * @param int $flags a bitmask for one or more flags.
     * @throws InvalidArgumentException if the given argument is not an integer value.
     */
    public function setFlags($flags)
    {
        if (!is_int($flags)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s" instead',
                __METHOD__,
                (is_object($flags) ? get_class($flags) : gettype($flags))
            ));
        }
        
        $this->flags = $flags;
    }

    /**
     * {@inheritDoc}
     */
    public function getFlags()
    {
        return $this->flags;
    }
    
    /**
     * Set the line number.
     *
     * @param int $lineNumber the line number.
     * @throws InvalidArgumentException if the given argument is not a numeric value.
     * @throws LogicException if the given line number is a negative number.
     */
    public function setLineNumber($lineNumber)
    {
        if (!is_numeric($lineNumber)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s" instead',
                __METHOD__,
                (is_object($lineNumber) ? get_class($lineNumber) : gettype($lineNumber))
            ));
        } else if ($lineNumber < 0) {
            throw new \LogicException(sprintf(
                '%s: line number cannot be a negative number.',
                __METHOD__
            ));
        }
        
        $this->lineNumber = (int) $lineNumber;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }
    
    /**
     * Set the specified collection of nodes as children of this node.
     *
     * @param array|Traversable $nodes a collection of nodes.
     * @throws InvalidArgumentException if the given argument is not an array of Traversable object.
     */
    public function setChildren($nodes)
    {
        if ($nodes instanceof \Traversable) {
            $nodes = iterator_to_array($nodes);
        }
    
        if (!is_array($elements)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or instance of the Traversable; received "%s"',
                __METHOD__,
                (is_object($elements) ? get_class($elements) : gettype($elements))
            ));
        }
        
        $this->children = array_filter($nodes, array($this, 'isNode'));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Checks if the object is a Node.
     *
     * @param mixed $obj the object to test.
     * @return bool true if the given object is a Node, false otherwise.
     */
    private function isNode($obj)
    {
        return (is_object($obj) && $obj instanceof NodeInterface);
    }
}
