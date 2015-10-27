<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\ReferenceException;

class VariableNode extends Node
{
    /**
     * The variable name.
     *
     * @var string
     */
    private $name;

    /**
     * Construct a new Variable.
     *
     * @param string $name the name by which this variable is identified.
     * @param array|Traversable $nodes (optional) a collection of nodes.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($name, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setName($name);
    }
    
    /**
     * {@inheritDoc}
     *
     * @throws ReferenceException if the variable does not exist within the specified context.
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $name = $this->getName();
        if (!$context->offsetExists($name)) {
            throw new ReferenceException(sprintf('name "%s" is not defined', $name), $this->getLineNumber());
        }

        return $context[$name];
    }

    /**
     * Returns the name for this variable.
     *
     * @return string the name for this variable.
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set the name for this variable.
     *
     * @param string $value the name for this variable.
     * @throws InvalidArgumentException if the specified argument is not a string value.
     */
    private function setName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($name)) ? get_class($name) : gettype($name)
            ));
        }
    
        $this->name = $name;
    }
}
