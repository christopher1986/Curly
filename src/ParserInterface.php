<?php

namespace Curly;

/**
 * The ParserInterface will parse an input string into an abstract syntax tree.
 *
 * A class that implements this interface does not only perform syntactical analysis on an
 * input string but also lexical analysis. After the lexical analysis the parser can parse
 * the tokens created in the lexical stage into an abstract syntax tree or delegate parts 
 * of this process to SubparserInterface instances.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface ParserInterface
{
    /**
     * Creates an abstract syntax tree from the given input string.
     *
     * @param string $input the string to parse.
     * @return NodeInterface root node of an abstract syntax tree.
     */
    public function parse($input);
}
