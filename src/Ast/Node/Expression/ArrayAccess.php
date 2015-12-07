<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\KeyException;
use Curly\Parser\Exception\TypeException;

/**
 * The ArrayAccess node represents an expression where a value of an array is being accessed. 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ArrayAccess extends Node
{
    /**
     * A node that represents the array to access.
     *
     * @var NodeInterface
     */
    private $array = null;

    /**
     * Construct a new ArrayAccess.
     *
     * @param NodeInterface $array the array which will be accessed.
     * @param array|Traversable $indices a collection of index nodes.
     * @param array|Traversable $nodes (optional) a collection of nodes.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $array, $children = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($children, $lineNumber, $flags);
        $this->setArray($array);
    }

    /**
     * {@inheritDoc}
     *
     * @throws TypeException if the specified node is not an array or a PHP {@link ArrayAccess} object.
     * @throws TypeException if one or more indices are not scalar values.
     * @throws KeyException if the specified key or index does not exist.
     * @link http://php.net/manual/en/class.arrayaccess.php ArrayAccess interface
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {    
        $array = $this->getArray()->render($context, $out);
        foreach ($this->getChildren() as $node) {
            $index = $node->render($context, $out);
            if (!is_scalar($index)) {
                throw new TypeException(sprintf('%s cannot be interpreted as index', gettype($index)), $node->getLineNumber()); 
            }
            
            // the rendered node is not a collection type.
            if (!is_array($array) && !$array instanceof \ArrayAccess) {
                throw new TypeException(sprintf('%s is not an array', gettype($array)), $this->getArray()->getLineNumber()); 
            }
            
            // the specifed index is non-existent.
            if (is_array($array) && !array_key_exists($index, $array) || is_object($array) && !$array->offsetExists($index)) {
                throw new KeyException(sprintf('undefined index: %s', $index), $node->getLineNumber());
            }
                
            $array = $array[$index];          
        }
        
        return $array;
    }
    
    /**
     * Set the array node which will be accessed.
     *
     * @param NodeInterface $node the node that represents an array.
     */
    public function setArray(NodeInterface $node)
    {
        $this->array = $node;
    }
    
    /**
     * Returns the array node which will be accessed.
     *
     * @return NodeInterface the node that represents the array.
     */
    private function getArray()
    {
        return $this->array;
    }
}
