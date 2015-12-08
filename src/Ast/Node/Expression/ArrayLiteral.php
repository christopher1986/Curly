<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\Node\Entry;
use Curly\Collection\ArrayObject;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The ArrayLiteral node represents a collection type such as a numeric or associative array.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ArrayLiteral extends Node
{
    /**
     * A flag for numeric arrays.
     *
     * @var int
     */
    const TYPE_NUMERIC = 0x04;

    /**
     * A flag for associative arrays.
     *
     * @var int
     */
    const TYPE_ASSOCIATIVE = 0x08;

    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $array = new ArrayObject();
        foreach ($this->getChildren() as $node) {
            if ($node instanceof Entry) {
                list($key, $value) = $node->render($context, $out);    
                $array->offsetSet($key, $value);
            } else {
                $array->offsetSet(null, $node->render($context, $out));
            }
        }

        return $array;
    }
}
