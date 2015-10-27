<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\Node\EntryNode;
use Curly\Collection\ArrayObject;
use Curly\Io\Stream\OutputStreamInterface;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ArrayNode extends Node
{
    /**
     * A flag to indicate a numeric array.
     *
     * @var int
     */
    const TYPE_NUMERIC = 0x01;

    /**
     * A flag to indicate an associative array.
     *
     * @var int
     */
    const TYPE_ASSOCIATIVE = 0x02;

    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $array = new ArrayObject();
        foreach ($this->getChildren() as $node) {
            if ($node instanceof EntryNode) {
                list($key, $value) = $node->render($context, $out);    
                $array->offsetSet($key, $value);
            } else {
                $array->offsetSet(null, $node->render($context, $out));
            }
        }

        return $array;
    }
}
