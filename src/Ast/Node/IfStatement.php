<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\TemplateContext;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The IfStatement node is responsible for rendering an if statement.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class IfStatement extends Node
{
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $context->push(new TemplateContext());
        
        $conditions = $this->getChildren();
        foreach ($conditions as $condition) {
            if ($condition->isTrue($context, $out)) {
                $condition->render($context, $out);
                break;
            }
        }
        
        $context->pop();
    }

    /**
     * Returns true if the specified object is a {@link Conditional} instance.
     *
     * @param mixed $obj the object to test.
     * @return bool true if the specified object is a {@link Conditional} instance, false otherwise.
     */
    protected function isNode($obj)
    {
        return (is_object($obj) && $obj instanceof Conditional);
    }
}
