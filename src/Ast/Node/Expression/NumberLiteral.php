<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The NumberLiteral node represents a numeric literal such as integer or floating point values.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class NumberLiteral extends Node
{      
    /**
     * A flag for integer values.
     *
     * @var int
     */
    const TYPE_INTEGER = 0x01;

    /**
     * A flag for floating point values.
     *
     * @var int
     */
    const TYPE_FLOAT = 0x02;

    /**
     * The number value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Construct a new NumberLiteral.
     *
     * @param int|float $value the number value this node will contain.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($value, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setNumberValue($value);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        return $this->getNumberValue();
    }
    
    /**
     * Set the number value this node will contain.
     *
     * @param int|float $value the number value this node will contain.
     * @throws InvalidArgumentException if the specified argument is not a numeric value.
     */
    private function setNumberValue($value)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric value; received "%s"',
                __METHOD__,
                (is_object($value)) ? get_class($value) : gettype($value)
            ));
        }
    
        $this->value = $value;
    }
    
    /**
     * Returns the number value contained by this node.
     *
     * @return int|float the number value contained by this node.
     */
    private function getNumberValue()
    {
        return $this->value;
    }
}
