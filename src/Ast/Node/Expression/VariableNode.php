<?php

namespace Curly\Ast\Node\Expression;

use Curly\Ast\Node;

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
    
    /**
     * Returns the name for this variable.
     *
     * @return string the name for this variable.
     */
    private function getName()
    {
        return $this->name;
    }
}
