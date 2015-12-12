<?php

namespace Curly\Ast\Node\Expression;

use ErrorException;
use ReflectionClass;
use ReflectionException;

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
     * A cache of instantiated reflection classes.
     *
     * @var array
     */
    private static $reflectionCache = array();
    
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
            $args = array();
            foreach ($this->getChildren() as $node) {
                $args[] = $node->render($context, $out);
            }
        
            $found = false;
            $value = $this->invokeMethod($object, $name, $args, $found);
            if ($found) {
                return $value;
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
    
    /**
     * Returns the value for the specified property name and object.
     *
     * @param object $object the object whose method will be called.
     * @param string $name the name of the method to invoke.
     * @param array $args (optional) a collection of arguments with which to invoke the method.
     * @param bool $found (optional) a flag that will be set to true if the method was found.
     * @return mixed|null the return value of the invoked method, or null.
     */
    private function invokeMethod($object, $name, array $args = null, &$found = false)
    {
        // stop early.
        if (!is_object($object)) {
            return null;   
        }
        
        // cache for performance.
        $class = get_class($object);
        if (!array_key_exists($class, self::$reflectionCache)) {
            self::$reflectionCache[$class] = new ReflectionClass($object);
        }
        
        $reflClass = self::$reflectionCache[$class];
        if ($reflClass->hasMethod($name)) {   
            $reflMethod = $reflClass->getMethod($name);
            if ($found = $reflMethod->isPublic()) {
                try {
                    return (is_array($args)) ? $reflMethod->invokeArgs($object, $args) : $reflMethod->invoke($object);
                } catch (ReflectionException $e) {
                    $found = false;
                } catch (ErrorException $e) {
                    $found = false;
                }   
            }
        }
        
        return null;
    }
}
