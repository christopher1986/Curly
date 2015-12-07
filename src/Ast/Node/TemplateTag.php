<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\NoSuchMethodException;

/**
 * The TemplateTag node is responsible for rendering a template tag.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class TemplateTag extends Node
{
    /**
     * The tag which will be invoked.
     *
     * @var object
     */
    private $tag;

    
    /**
     * A collection of argument to pass to the tag.
     *
     * @var array
     */
    private $arguments;
    
    /**
     * Construct a new TemplateTag.
     *
     * @param object $tag the tag to invoke.
     * @param array $arguments (optional) a collection of arguments passed to the tag.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($tag, $arguments = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setTag($tag);
        $this->setArguments($arguments);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $tag  = $this->getTag();
        $args = array();
        foreach ($this->getArguments() as $node) {
            $args[] = $node->render($context, $out);
        }
        
        $callable = array($tag, 'call');
        if (!is_callable($callable)) {
            throw new NoSuchMethodException(sprintf(
                'missing publicly accessible "call" method for %s',
                get_class($tag)
            ));
        }
        
        return call_user_func_array($callable, $args);
    }
    
    /**
     * Set the tag which will be invoked when rendering this node.
     *
     * @param object $tag the tag to invoke.
     * @throws InvalidArgumentException if the given argument is an object.
     */
    private function setTag($tag)
    {
        if (!is_object($tag)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an object; received "%s"',
                __METHOD__,
                gettype($tag)
            ));
        }
    
        $this->tag = $tag;
    }
    
    /**
     * Returns the tag which will be invoked when this node is rendered.
     *
     * @return object the tag to invoke.
     */
    private function getTag()
    {
        return $this->tag;
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
