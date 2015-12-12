<?php

namespace Curly\Ast;

use Curly\ContextInterface;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Node implements NodeInterface
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
     * Construct a new Node.
     *
     * @param array|Traversable $children (optional) a collection of nodes.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($children = array(), $lineNumber = -1, $flags = 0x00)
    {
        $this->setChildren($children);
        $this->setFlags($flags);
        
        if ($lineNumber >= 0) {
            $this->setLineNumber($lineNumber);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function setFlags($flags = 0x00)
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
    public function hasFlags($flags)
    {
        return (($this->flags & $flags) === $flags);
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
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
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
        }

        $this->children = array_filter($nodes, array($this, 'isNode'));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        if ($this->children === null) {
            $this->children = array();
        }
    
        return $this->children;
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
     * Returns true if the specified object is a {@link NodeInterface} instance.
     *
     * @param mixed $obj the object to test.
     * @return bool true if the specified object is a {@link NodeInterface} instance, false otherwise.
     */
    protected function isNode($obj)
    {
        return (is_object($obj) && $obj instanceof NodeInterface);
    }
}
