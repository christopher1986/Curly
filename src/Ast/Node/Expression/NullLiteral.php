<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The NullLiteral node represents a "NULL" literal.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class NullLiteral extends Node
{
    /**
     * Construct a new NullLiteral.
     *
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        return null;
    }
}
