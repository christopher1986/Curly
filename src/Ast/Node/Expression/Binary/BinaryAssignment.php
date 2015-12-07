<?php

namespace Curly\Ast\Node\Expression\Binary;

use Curly\ContextInterface;
use Curly\Ast\NodeInterface;
use Curly\Ast\Node\Expression\AbstractBinary;
use Curly\Ast\Node\Expression\Variable;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\SyntaxException;

/**
 * The BinaryAssignment node represents an assignment operator and it's left and right operand.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class BinaryAssignment extends AbstractBinary
{
    /**
     * Construct a new BinaryAssignment.
     *
     * @param Variable $left a variable node.
     * @param NodeInterface $right an expression node.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct(Variable $left, NodeInterface $right, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct($left, $right, $lineNumber, $flags);
    }

    /**
     * {@inheritDoc}
     *
     * @throws SyntaxException if the left operand is not a {@link Variable} instance.
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        list($left, $right) = $this->getChildren();
        
        if (!($left instanceof Variable)) {
            throw new SyntaxException(sprintf('Can\'t assign to %s', gettype($left->render($context, $out))), $left->getLineNumber());
        }
    
        $context[$left->getIdentifier()] = $right->render($context, $out);
    }
}
