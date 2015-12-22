<?php

namespace Curly\Parser\Visitor;

use Curly\Ast\NodeInterface;

/**
 * The VisitorInterface defines a way to traverse all nodes within an abstract syntax tree.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface VisitorInterface
{
     /** 
     * Visit the specified node.
     *
     * @param NodeInterface $node the node that is being visited.
     */
    public function visit(NodeInterface $node);
}
