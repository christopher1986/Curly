<?php

namespace Curly\Ast\Node\Expression\Binary;

use Curly\ContextInterface;
use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\AbstractBinaryNode;
use Curly\Ast\Node\Expression\VariableNode;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\SyntaxException;

/**
 * The AssignmentNode is responsible for rendering the assignment operator and it's left and right operand.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class AssignmentNode extends AbstractBinaryNode
{
    /**
     * Construct a new AssignmentNode.
     *
     * @param VariableNode $left a variable node.
     * @param NodeInterface $right an expression node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(VariableNode $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($left, $right, $lineNumber, $flags);
    }

    /**
     * {@inheritDoc}
     *
     * @throws SyntaxException if the left operand is not a {@link VariableNode} instance.
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        list($left, $right) = $this->getChildren();
        
        if (!($left instanceof VariableNode)) {
            throw new SyntaxException(sprintf('Can\'t assign to %s', gettype($left->render($context, $out))), $left->getLineNumber());
        }
    
        $context[$left->getName()] = $right->render($context, $out);
    }
}
