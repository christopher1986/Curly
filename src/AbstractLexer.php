<?php

namespace Curly;

/**
 * The AbstractLexer provides methods for a lexer which can be in or more contexts during the lexical analysis.
 * Whether a lexer can be in or more contexts simultaneously is something that has to be determined by the 
 * implementing class.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractLexer implements LexerInterface
{
    /**
     * the context is the current position of the scanner within a sequence of characters. 
     *
     * @var int
     */ 
    protected $context = 0x00;
    
    /**
     * Add the given context to scanner.
     *
     * The scanner will be in the given context after this operation returns.
     * This can be tested for using the {@link AbstractScanner::hasContext($context)} method.
     *
     * @parem int context the context the place the scanner in.
     */
    public function addContext($context)
    {
        $this->context |= $context;
    }
    
    /**
     * Replaces the current context of the scanner.
     * 
     * If the context is omitted the context of the will be reset to it's initial value.
     *
     * @param int a new context that will replace the current context. 
     */
    public function setContext($context = 0x00)
    {
        $this->context = $context;
    }
    
    /**
     * Determine whether the scanner is currently in the given context.
     *
     * @param int context the context whose presence will be tested.
     * @return bool true if the scanner is currenty positioned in the given context, false otherwise.
     */
    public function hasContext($context)
    {
        return (($this->context & $context) === $context);
    }
    
    /**
     * Removes if present the given context.
     *
     * The scanner will no longer be in the given context after this operation returns.
     * This can be tested using the {@link AbstractScanner::hasContext($context)} method.
     *
     * @param int the context which the scanner will no longer be in.
     */
    public function removeContext($context)
    {
        $this->context &= ~$context;
    }
    
    /**
     * Resets the context to it's original value.
     */
    public function resetContext()
    {
        $this->context = 0x00;
    }
    
    /**
     * Returns true if the scanner has no context.
     *
     * @return bool true if no context is set, false otherwise.
     */
    public function isContextFree()
    {
        return ($this->context === 0x00);
    }
}
