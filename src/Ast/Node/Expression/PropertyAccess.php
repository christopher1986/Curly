<?php

namespace Curly\Ast\Node\Expression;

use ErrorException;
use ReflectionClass;
use ReflectionException;
use stdClass;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\AttributeException;
use Curly\Parser\Exception\TypeException;

/**
 * The PropertyAccess node represents an expression where the property of an object is being accessed. 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class PropertyAccess extends Node
{
    /**
     * A cache of instantiated reflection classes.
     *
     * @var array
     */
    private static $reflectionCache = array();

    /**
     * The object containing the property.
     *
     * @var NodeInterface
     */
    private $node = null;

    /**
     * The name of the property to access.
     *
     * @var SimpleName
     */
    private $name = null;

    /**
     * Construct a new PropertyAccess.
     *
     * @param NodeInterface $node the node which when rendered should return an object.
     * @param SimpleName $name the name of the property to access.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $node, SimpleName $name, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setObject($node);
        $this->setName($name);
    }

    /**
     * {@inheritDoc}
     *
     * @throws TypeException if the rendered node is not an object.
     * @throws AttributeException if the property does not exist or is not accessible.
     * @link http://php.net/manual/en/class.arrayaccess.php ArrayAccess interface
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $object = $this->getObject()->render($context, $out);
        $name   = $this->getName()->render($context, $out);

        if (is_object($object)) {
            $found = false;
            $value = $this->readProperty($object, $name, $found);
            if ($found) {
                return $value;
            }
        }
        
        if ($this->hasFlags(NodeInterface::E_STRICT)) {
            if (!is_object($object)) {
                throw new TypeException(sprintf('cannot use %s as object', gettype($object)), $this->getObject()->getLineNumber());
            }            
            throw new AttributeException(sprintf('%s has no property "%s"', get_class($object), $name), $this->getName()->getLineNumber()); 
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
     * @param object $object the object whose properties will be read.
     * @param string $name the name of the property whose value to return.
     * @param bool $found (optional) a flag that will be set to true if the property was found.
     * @return mixed|null the property value, or null.
     */
    private function readProperty($object, $name, &$found = false)
    {
        // stop early.
        if (!is_object($object)) {
            return null;   
        }
        
        // read dynamic objects.
        if ($object instanceof stdClass) {
            return ($found = property_exists($object, $name)) ? $object->{$name} : null;
        }
        
        // add object to cache.
        $class = get_class($object);
        if (!isset(self::$reflectionCache[$class])) {
            self::$reflectionCache[$class] = new ReflectionClass($object);
        }
        
        $reflClass = self::$reflectionCache[$class];
        if ($reflClass->hasProperty($name)) {
            $reflProp = $reflClass->getProperty($name);
            if ($found = $reflProp->isPublic()) {
                return $reflProp->getValue($object);
            }
        }
        
        // camelcase method name.
        $name = ucfirst($name);
        if (strpos($name, '_') !== false) {
            $name = join('', array_map('ucfirst', explode('_', $name)));
        }
        
        $types = array('get', 'is', 'has');
        foreach ($types as $type) {
            $method = sprintf('%s%s', $type, $name);
            if ($reflClass->hasMethod($method)) {            
                $reflMethod = $reflClass->getMethod($method);
                if ($found = ($reflMethod->isPublic() && $reflMethod->getNumberOfRequiredParameters() === 0)) {
                    try {
                        return $reflMethod->invoke($object);
                    } catch (ReflectionException $e) {
                        $found = false;
                    } catch (ErrorException $e) {
                        $found = false;
                    }
                }
            }
        }
        
        return null;
    }
}
