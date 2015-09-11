<?php

namespace Curly\Ast\Node\Expression;

use Curly\Ast\Node;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ScalarNode extends Node
{      
    /**
     * Possible data types.
     */
    const TYPE_INTEGER = 0x0001;
    const TYPE_FLOAT   = 0x0002;
    const TYPE_BOOLEAN = 0x0004;
    const TYPE_STRING  = 0x0008;
    const TYPE_MIXED   = 0x0010;

    /**
     * The value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Construct a new Scalar node.
     *
     * @param scalar $value the value for this scalar node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($value, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setValue($value);
    }
    
    /**
     * Set the value for this scalar node.
     *
     * @param scalar $value the value.
     * @throws InvalidArgumentException if the specified argument is not a scalar value.
     */
    private function setValue($value)
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: a literal must be scalar type; received "%s"',
                __METHOD__,
                (is_object($value)) ? get_class($value) : gettype($value)
            ));
        }
    
        $this->value = $value;
    }
    
    /**
     * Returns the value for this scalar node.
     *
     * @return scalar the value.
     */
    private function getValue()
    {
        return $this->value;
    }
}
