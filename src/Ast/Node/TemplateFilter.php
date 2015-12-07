<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\NoSuchMethodException;

/**
 * The TemplateFilter node is responsible for rendering a template filter.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class TemplateFilter extends Node
{
    /**
     * The filter which will be invoked.
     *
     * @var object
     */
    private $filter;

    /**
     * A collection of argument to pass to the filter.
     *
     * @var array
     */
    private $arguments = array();
    
    /**
     * Construct a new TemplateFilter.
     *
     * @param NodeInterface the node on which to apply the filter.
     * @param object $filter the filter to invoke.
     * @param array $arguments (optional) a collection of arguments passed to the filter.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     * @see TemplateFilter::setFilter($filter)
     * @see TemplateFilter::setArguments($arguments)
     */
    public function __construct(NodeInterface $node, $filter, $arguments = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array($node), $lineNumber, $flags);
        $this->setFilter($filter);
        $this->setArguments($arguments);
    }

    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    { 
        $filter = $this->getFilter();
        $nodes  = $this->getChildren();
        $node   = reset($nodes);
        
        $args = array($node->render($context, $out));
        foreach ($this->getArguments() as $node) {
            $args[] = $node->render($context, $out);
        }

        $callable = array($filter, 'filter');
        if (!is_callable($callable)) {
            throw new NoSuchMethodException(sprintf(
                'missing publicly accessible "filter" method for %s',
                get_class($filter)
            ));
        }
        
        return call_user_func_array($callable, $args);
    }
    
    /**
     * {@inheritDoc}
     *
     * A TemplateFilter operates on a single node; providing more or less nodes will raise an exception.
     *
     * @throws LogicException if the specified collection contains more or less than one node.
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
        } else if (($count = count($nodes)) != 1) {
            throw new \LogicException(sprintf(
                '%s: expects exactly 1 child node; received "%d" node(s)',
                __METHOD__,
                $count
            ));
        }

        parent::setChildren($nodes);
    }
    
    /**
     * Set the filter which will be invoked when rendering this node.
     *
     * @param object $filter the filter to invoke.
     * @throws InvalidArgumentException if the given argument is an object.
     */
    private function setFilter($filter)
    {
        if (!is_object($filter)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an object; received "%s"',
                __METHOD__,
                gettype($filter)
            ));
        }
    
        $this->filter = $filter;
    }
    
    /**
     * Returns the filter which will be invoked when this node is rendered.
     *
     * @return object a filter to invoke.
     */
    private function getFilter()
    {
        return $this->filter;
    }
    
    /**
     * Set a collection of arguments which will be passed to the filter.
     *
     * @param array|Traversable $arguments a collection of arguments passed to the filter.
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
     * Returns a collection of arguments which will be passed to the filter.
     *
     * @return array a collection of arguments to pass to the filter.
     */
    private function getArguments()
    {    
        return $this->arguments;
    }
}
