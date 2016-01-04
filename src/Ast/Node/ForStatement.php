<?php

namespace Curly\Ast\Node;

use SplFixedArray;
use Traversable;

use Curly\ContextInterface;
use Curly\TemplateContext;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\Variable;
use Curly\Parser\Exception\SyntaxException;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The ForStatement node is responsible for rendering a for loop statement.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ForStatement extends Node
{   
    /**
     * The loop variables.
     *
     * @var array
     */
    private $variables = array();

    /**
     * A sequence of elements to iterate over.
     *
     * @var NodeInterface
     */
    private $sequence;
    
    /**
     * Construct a new ForStatement.
     *
     * @param array $variables a collection containing loop variables.
     * @param NodeInterface $sequence a sequence of elements to iterate over.
     * @param array|Traversable $nodes (optional) a collection of nodes.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(array $variables, NodeInterface $sequence, $children = array(), $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($children, $lineNumber, $flags);
        $this->setVariables($variables);
        $this->setSequence($sequence);
    }

    /**
     * {@inheritDoc}
     *
     * @throws TypeException if the underlying sequence of this node is not iterable.
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $context->push(new TemplateContext());
        
        $varNames = $this->getVariableNames($context, $out);        
        $hasKey   = (count($varNames) === 2);

        $elements = $this->getSequence()->render($context, $out);
        if (!is_array($elements) && !$elements instanceof Traversable) {
            throw new TypeException(sprintf('%s is not iterable', gettype($elements)), $this->getSequence()->getLineNumber()); 
        }
        
        $rendered = array();
        $loopVars = (object) array(
            'counter0' => 0, 
            'counter'  => 1,
            'size'     => count($elements),
            'last'     => false
        );
        
        foreach ($elements as $key => $element) {
            if ($hasKey) {
                $context[$varNames[0]] = $key;
                $context[$varNames[1]] = $element;
                $context['forloop']    = $loopVars;
            } else {
                $context[$varNames[0]] = $element;
                $context['forloop']    = $loopVars;
            }

            foreach ($this->getChildren() as $node) {
                $rendered[] = $node->render($context, $out);
            }
            
            $loopVars->counter0 = $loopVars->counter;
            $loopVars->counter  = $loopVars->counter + 1;
            $loopVars->last     = ($loopVars->size === $loopVars->counter);
        }

        $context->pop();
        
        return implode('', $rendered);
    }
    
    /**
     * Returns a collection of variable names.
     *
     * @param ContextInterface $context the template context.
     * @param OutputStreamInterface $out the output stream.
     * @return array a numeric array containing variable names.
     * @throws SyntaxException if a variable is not a {@link Variable} instance.
     */
    private function getVariableNames(ContextInterface $context, OutputStreamInterface $out)
    {
        $variables = array();
        foreach ($this->getVariables() as $variable) {
            // values can only be assigned to variables.
            if (!$variable instanceof Variable) {
                throw new SyntaxException(sprintf('Can\'t assign to %s', gettype($variable->render($context, $out))), $variable->getLineNumber());
            }
            
            $variables[] = $variable->getIdentifier();
        }
        
        return $variables;
    }   
    
    /**
     * Set the loop variables.
     *
     * @param array|Traversable $variables a collection of loop variables.
     * @throws InvalidArgumentException if the given argument is not an array or Traversable object.
     */
    private function setVariables($variables)
    {
        if ($variables instanceof Traversable) {
            $variables = iterator_to_array($variables);
        }
    
        if (!is_array($variables)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array or Traversable object; received "%s"',
                __METHOD__,
                (is_object($variables) ? get_class($variables) : gettype($variables))
            ));
        }
        
        $variables = array_filter($variables, array($this, 'isNode'));
        $this->variables = array_splice($variables, 0, 2);
    }
    
    /**
     * {@inheritDoc}
     */
    private function getVariables()
    {    
        return $this->variables;
    }
    
    /**
     * Set a node which represents the sequence of elements to iterate over.
     *
     * @param NodeInterface $sequence a sequence of elements.
     */
    private function setSequence(NodeInterface $sequence)
    {
        $this->sequence = $sequence;
    }
    
    /**
     * Returns the node which represents the sequence of elements to iterate over.
     *
     * @return NodeInterface a sequence of elements.
     */
    private function getSequence()
    {
        return $this->sequence;
    }
}
