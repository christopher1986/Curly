<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\KeyException;
use Curly\Parser\Exception\TypeException;

/**
 * The ArrayAccess node represents an expression an array is being accessed. 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ArrayAccess extends Node
{
    /**
     * The array to access.
     *
     * @var NodeInterface
     */
    private $node = null;
    
    /**
     * The offset whose value to obtain.
     *
     * @var NodeInterface
     */
    private $offset = null;

    /**
     * Construct a new ArrayAccess. 
     *
     * @param NodeInterface $node the node which when rendered should return a collection type.
     * @param NodeInterface $offset the offset whose value to return.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $node, $offset, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setArray($node);
        $this->setOffset($offset);
    }

    /**
     * {@inheritDoc}
     *
     * @throws TypeException if the rendered node is not a collection type.
     * @throws KeyException if the specified offset is not defined.
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $array  = $this->getArray()->render($context, $out);
        $offset = $this->getOffset()->render($context, $out);
        
        if (is_array($array) && array_key_exists($offset, $array)) {
            return $array[$offset];
        } else if ($array instanceof \ArrayAccess && $array->offsetExists($offset)) {
            return $array[$offset];
        }
        
        if ($this->hasFlags(NodeInterface::E_STRICT) {
            if (!(is_array($array) || $array instanceof \ArrayAccess)) {
                throw new TypeException(sprintf('cannot use %s as array', gettype($array)), $this->getArray()->getLineNumber());
            }
            throw new KeyException(sprintf('undefined offset %s', $offset), $this->getOffset()->getLineNumber());
        }
        
        return null;
    }
    
    /**
     * Set the node that represents the array whose value to access.
     *
     * @param NodeInterface $node the node that represents the array whose value to access.
     */
    public function setArray(NodeInterface $node)
    {
        $this->node = $node;
    }
    
    /**
     * Returns the node that represents the array whose value to access.
     *
     * @return NodeInterface the node that represents the array whose value to access.
     */
    private function getArray()
    {
        return $this->node;
    }
    
    /**
     * Set the node containing the offset whose value will be returned.
     *
     * @param NodeInterface $node the node containing the offset.
     */
    public function setOffset(NodeInterface $node)
    {
        $this->offset = $node;
    }
    
    /**
     * Returns the node containing the offset whose value to return.
     *
     * @return NodeInterface the node containing the offset.
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
