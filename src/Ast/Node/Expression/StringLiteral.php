<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The StringLiteral node represents a string literal.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class StringLiteral extends Node
{
    /**
     * The string value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Construct a new StringLiteral.
     *
     * @param string $value the string value this node will contain.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($value, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setStringValue($value);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        return $this->getStringValue();
    }
    
    /**
     * Set the string value this node will contain.
     *
     * @param string $value the string value this node will contain.
     * @throws InvalidArgumentException if the specified argument is not a string value.
     */
    private function setStringValue($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string value; received "%s"',
                __METHOD__,
                (is_object($value)) ? get_class($value) : gettype($value)
            ));
        }
    
        $this->value = $value;
    }
    
    /**
     * Returns the string value contained by this node.
     *
     * @return string the string value contained by this node.
     */
    private function getStringValue()
    {
        return $this->value;
    }
}
