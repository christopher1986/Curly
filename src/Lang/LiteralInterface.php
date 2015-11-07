<?php

namespace Curly\Lang;

use Curly\ParserInterface;
use Curly\Parser\Stream\TokenStream;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface LiteralInterface
{
    /**
     * Returns the token type which this literal is associated with.
     *
     * @return int the token type this literal is associated with.
     * @see TokenInterface::getType()
     * @see Lexer
     */
    public function getIdentifier();
    
    /**
     * Creates part of the abstract syntax tree by parsing a collection of tokens.
     *
     * @param ParserInterface $parser the template parser.
     * @param TokenStream the stream of tokens to parse.
     * @return NodeInterface|null a node or null if not enough tokens have been parsed yet.
     */
    public function parse(ParserInterface $parser, TokenStream $stream);
}
