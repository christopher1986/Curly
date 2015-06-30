<?php

namespace Curly;

use SplStack;

/**
 * The SubparserInterface parses a collection of tokens into a node which is part of an abstract syntax tree. 
 * 
 * Because classes implementing this interface operate on a collection of tokens and not on an input string
 * it's only possible to use a subparser after the lexical analysis has taken place or to make it part of a 
 * larger parsing process where the input string has been tokenized by a ParserInterface instance.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface SubparserInterface
{
    /**
     * Creates part of the abstract syntax tree by parsing a last-in-first-out (LIFO) stack
     * from a {@link LexerInterface} instance.
     *
     * @param SplStack $tokens collection of tokens.
     */
    public function parse(SplStack $tokens);
}
