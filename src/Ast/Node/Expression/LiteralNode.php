<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class LiteralNode extends Node
{   
    /**
     * The value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Construct a new Literal node.
     *
     * @param mixed $value the value for this literal node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($value = null, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setValue($value);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        return $this->getValue();
    }
    
    /**
     * Set the value for this literal node.
     *
     * @param mixed $value the value.
     */
    private function setValue($value)
    {    
        $this->value = $value;
    }
    
    /**
     * Returns if present the value for this literal node.
     *
     * @return mixed the value.
     */
    private function getValue()
    {
        return $this->value;
    }
}
