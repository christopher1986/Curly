<?php 

namespace Curly;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface EngineInterface
{
    /**
     * Returns a set of reserved operator symbols.
     *
     * @return SetInterface a set of operator symbols.
     */
    public function getOperatorSymbols();
    
    /**
     * Returns a lexer to perform lexical analysis on an input string. 
     *
     * @return LexerInterface a lexer.
     */
    public function getLexer();
    
    /**
     * Returns a parser to perform syntactical analysis on a sequence of tokens.
     *
     * @return ParserInterface a parser.
     */
    public function getParser();
    
    /**
     * Returns a library which contains registered filter, operators and tags.
     *
     * @return LibraryInterface a library.
     */
    public function getLibrary();
}
