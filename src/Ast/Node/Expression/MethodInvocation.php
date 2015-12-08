<?php

namespace Curly\Ast\Node\Expression;

use ReflectionClass;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\AttributeException;
use Curly\Parser\Exception\TypeException;

/**
 * The MethodInvocation node represents an expression where the method of an object is invoked. 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class MethodInvocation extends Node
{
    /**
     * The object whose method to invoke.
     *
     * @var NodeInterface
     */
    private $node = null;

    /**
     * The name of the method to invoke.
     *
     * @var SimpleName
     */
    private $name = null;

    /**
     * Construct a new MethodInvocation.
     *
     * @param NodeInterface $node the node which when rendered should return an object.
     * @param SimpleName $name the name of the method to invoke.
     * @param array $arguments (optional) a collection of arguments with which the method will be invoked.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $node, SimpleName $name, array $arguments = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($arguments, $lineNumber, $flags);
        $this->setObject($node);
        $this->setName($name);
    }

    /**
     * {@inheritDoc}
     *
     * @throws TypeException if the rendered node is not an object.
     * @throws AttributeException if the method does not exist or is not accessible.
     * @link http://php.net/manual/en/class.arrayaccess.php ArrayAccess interface
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {    
        $object = $this->getObject()->render($context, $out);
        $name   = $this->getName()->render($context, $out);
        
        if (is_object($object)) {
            $reflClass = new ReflectionClass($object);
            if ($reflClass->hasMethod($name)) {
                $reflMethod = $reflClass->getMethod($name);
                if ($reflMethod->isPublic() && ($reflMethod->isVariadic() || $reflMethod->getNumberOfRequiredParameters() === count($this->getChildren()))) {
                    $args = array();
                    foreach ($this->getChildren() as $node) {
                        $args[] = $node->render($context, $out);
                    }
                
                    try {
                        return $reflMethod->invokeArgs($object, $args);
                    } catch (\ReflectionException $e) {
                        // fail silently.
                    }
                }
            }
        }
        
        if ($this->hasFlags(NodeInterface::E_STRICT)) {
            if (!is_object($object)) {
                throw new TypeException(sprintf('cannot use %s as object', gettype($object)), $this->getObject()->getLineNumber());
            }
            throw new AttributeException(sprintf('%s has no method "%s"', get_class($object), $name), $this->getName()->getLineNumber()); 
        }
        
        return null;
    }
    
    /**
     * Set the node that represents the object whose property to access.
     *
     * @param NodeInterface $node the node that represents the object whose property to access.
     */
    public function setObject(NodeInterface $node)
    {
        $this->node = $node;
    }
    
    /**
     * Returns the node that represents the object whose property to access.
     *
     * @return NodeInterface the node that represents the object whose property to access.
     */
    private function getObject()
    {
        return $this->node;
    }
    
    /**
     * Set the node that contains the name of the property to access.
     *
     * @param SimpleName $node the node containing the name of the property to access.
     */
    public function setName(SimpleName $node)
    {
        $this->name = $node;
    }
    
    /**
     * Returns the node containing the name of the property to access.
     *
     * @return SimpleName the node containing the name of the property to access.
     */
    private function getName()
    {
        return $this->name;
    }
}
