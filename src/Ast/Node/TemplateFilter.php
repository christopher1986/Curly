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
     * Construct a new TemplateFilter.
     *
     * @param object $filter the filter to invoke.
     * @param array $arguments (optional) a collection of arguments with which the filter will be invoked.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     * @see TemplateFilter::setFilter($filter)
     * @see TemplateFilter::setArguments($arguments)
     */
    public function __construct($filter, array $arguments = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($arguments, $lineNumber, $flags);
        $this->setFilter($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {        
        $args = array();
        foreach ($this->getChildren() as $node) {
            $args[] = $node->render($context, $out);
        }

        $filter   = $this->getFilter(); 
        $callable = array($filter, 'filter');
        if (!is_callable($callable)) {
            throw new NoSuchMethodException(sprintf('missing publicly accessible "filter" method for %s', get_class($filter)));
        }
        
        return call_user_func_array($callable, $args);
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
}
