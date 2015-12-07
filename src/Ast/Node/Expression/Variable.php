<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;
use Curly\Parser\Exception\ReferenceException;

/**
 * The Variable node represents a (template) variable.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Variable extends Node
{
    /**
     * The identifier of this node.
     *
     * @var string
     */
    private $identifier;

    /**
     * Construct a new Variable.
     *
     * @param string $identifier the identifier of this node.
     * @param array|Traversable $nodes (optional) a collection of nodes.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($identifier, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setIdentifier($identifier);
    }
    
    /**
     * {@inheritDoc}
     *
     * @throws ReferenceException if the variable does not exist within the specified context.
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $identifier = $this->getIdentifier();
        if (!$context->offsetExists($identifier)) {
            throw new ReferenceException(sprintf('name "%s" is not defined', $identifier), $this->getLineNumber());
        }

        return $context[$identifier];
    }

    /**
     * Set the identifier of this node to the specified value.
     *
     * @param string $identifier the identifier of this node.
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = (string) $identifier;
    }
    
    /**
     * Returns the identifier of this node.
     *
     * @return string the identifier of this node.
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
