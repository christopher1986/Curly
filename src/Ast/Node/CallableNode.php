<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The CallableNode is responsible for rendering a callback function.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class CallableNode extends Node
{
    /**
     * The callback function which will be invoked.
     *
     * @var callable
     */
    private $callable;
    
    /**
     * A collection of argument to pass to the callable.
     *
     * @var array
     */
    private $arguments;
    
    /**
     * Construct a new CallableNode.
     *
     * @param callable $callable the callback function to invoke.
     * @param array $arguments (optional) a collection of arguments passed to the filter.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($callable, $arguments = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setCallable($callable);
        $this->setArguments($arguments);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $args = array();
        foreach ($this->getArguments() as $node) {
            $args[] = $node->render($context, $out);
        }
        
        return call_user_func_array($this->getCallable(), $args);
    }
    
    /**
     * Set the callback function which will be invoked when rendering this node.
     *
     * @param callable $callable the callback function to invoke.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    private function setCallable($callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: ',
                __METHOD__,
                (is_object($callable)) ? get_class($callable) : gettype($callable)
            ));
        }
    
        $this->callable = $callable;
    }
    
    /**
     * Returns the callback function which will be invoked when this node is rendered.
     *
     * @return callable the callback function to invoke.
     */
    private function getCallable()
    {
        return $this->callable;
    }
    
    /**
     * Set a collection of arguments which will be passed to the callback function.
     *
     * @param array|Traversable $arguments a collection of arguments passed to the callback function.
     */
    private function setArguments($arguments)
    {
        if ($arguments instanceof \Traversable) {
            $arguments = iterator_to_array($arguments);
        }
    
        if (!is_array($arguments)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($arguments) ? get_class($arguments) : gettype($arguments))
            ));
        }

        $this->arguments = array_filter($arguments, array($this, 'isNode'));
    }
    
    /**
     * Returns a collection of arguments which will be passed to the callback function.
     *
     * @return array a collection of arguments to pass to the callback function.
     */
    private function getArguments()
    {    
        return $this->arguments;
    }
}
