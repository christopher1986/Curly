<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Ast\NodeInterface;
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
     * Construct a new Print.
     *
     * @param NodeInterface $node the node whose rendered value to print.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(NodeInterface $node, $lineNumber = -1, $flags = 0x00)
    {
        $this->setChildren(array($node));
        $this->setFlags($flags);
        
        if ($lineNumber >= 0) {
            $this->setLineNumber($lineNumber);
        }
    }

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
