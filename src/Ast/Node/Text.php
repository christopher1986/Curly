<?php

namespace Curly\Ast\Node;

use Curly\ContextInterface;
use Curly\Ast\Node;
use Curly\Io\Stream\OutputStreamInterface;

/**
 * The Text node is responsible for rendering plain text.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Text extends Node
{   
    /**
     * The plain text.
     *
     * @var string
     */
    private $text;

    /**
     * Construct a new Text.
     *
     * @param string $text a sequence of characters.
     * @param int $lineNumber (optional) the line number.
     * @param int $flags (optional) a bitmask for one or more flags.
     */
    public function __construct($text, $lineNumber = -1, $flags = 0x00)
    {
        parent::__construct(array(), $lineNumber, $flags);
        $this->setText($text);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context, OutputStreamInterface $out)
    {
        $out->write($this->getText());
    }
    
    /**
     * Set the text.
     *
     * @param string $text a sequence of characters.
     * @throws InvalidArgumentException if the specified argument is not a string type.
     */
    private function setText($text)
    {
        if (!is_string($text)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string type; received "%s"',
                __METHOD__,
                (is_object($text)) ? get_class($text) : gettype($text)
            ));
        }
    
        $this->text = $text;
    }
    
    /**
     * Returns the text.
     *
     * @return string a sequence of characters.
     */
    public function getText()
    {
        return $this->text;
    }
}
