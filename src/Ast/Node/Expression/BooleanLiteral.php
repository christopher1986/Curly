<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The BooleanLiteral node represents a boolean literal such as "true" or "false".
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class BooleanLiteral extends Node
{
    /**
     * The boolean value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Construct a new BooleanLiteral.
     *
     * @param boolean $value the boolean value this node will contain.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($value, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setBooleanValue($value);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        return $this->getBooleanValue();
    }
    
    /**
     * Set the boolean value this node will contain.
     *
     * @param boolean $value the boolean value this node will contain.
     * @throws InvalidArgumentException if the specified argument is not a boolean value.
     */
    private function setBooleanValue($value)
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string value; received "%s"',
                __METHOD__,
                (is_object($value)) ? get_class($value) : gettype($value)
            ));
        }
    
        $this->value = $value;
    }
    
    /**
     * Returns the boolean value contained by this node.
     *
     * @return boolean the boolean value contained by this node.
     */
    private function getBooleanValue()
    {
        return $this->value;
    }
}
