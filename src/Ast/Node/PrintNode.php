<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The PrintNode is responsible for rendering a print statement.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class PrintNode extends Node
{    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $children = $this->getChildren();
        if ($node = reset($children)) {
            $out->write($node->render($context, $out));
        }
    }
}
