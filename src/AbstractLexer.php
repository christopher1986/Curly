<?php

namespace Curly;

/**
 * The AbstractLexer provides methods for a lexer that can be in or more contexts during the lexical analysis.
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
     * the context is the current position of the lexer within a sequence of characters. 
     *
     * @var int
     */ 
    protected $context = 0x00;
    
    /**
     * Add the specified context to lexer.
     *
     * The lexer will be in the specified context after this operation returns.
     * This can be tested for using the {@link AbstractLexer::hasContext($context)} method.
     *
     * @parem int context the context the place the lexer in.
     */
    public function addContext($context)
    {
        $this->context |= $context;
    }
    
    /**
     * Replaces the current context of the lexer.
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
     * Determine whether the lexer is has the specified context.
     *
     * @param int context the context whose presence will be tested.
     * @return bool true if the lexer has the specified context, false otherwise.
     */
    public function hasContext($context)
    {
        return (($this->context & $context) === $context);
    }
    
    /**
     * Removes if present the specified context.
     *
     * The lexer will no longer be in the specified context after this operation returns.
     * This can be tested using the {@link AbstractLexer::hasContext($context)} method.
     *
     * @param int the context which the lexer will no longer be in.
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
     * Returns true if the lexer has no context.
     *
     * @return bool true if no context is set, false otherwise.
     */
    public function isContextFree()
    {
        return ($this->context === 0x00);
    }
}
