<?php

namespace Curly\Ast\Node\Expression;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The SimpleName node represents an identifier other than a keyword, boolean literal or null literal.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class SimpleName extends Node
{
    /**
     * The identifier.
     *
     * @var string
     */
    private $identifier;

    /**
     * Construct a new SimpleName.
     *
     * @param scalar $identifier the identifier of this node.
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
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        return $this->getIdentifier();
    }
    
    /**
     * Set the identifier of this node to the specified value.
     *
     * @param string $identifier the identifier of this node.
     * @throws InvalidArgumentException if the specified argument is not a string.
     */
    public function setIdentifier($identifier)
    {
        if (!is_string($identifier)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string value; received "%s"',
                __METHOD__,
                (is_object($identifier)) ? get_class($identifier) : gettype($identifier)
            ));
        }
    
        $this->identifier = $identifier;
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
