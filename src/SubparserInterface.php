<?php

namespace Curly;

use Curly\Collection\Stream\TokenStream;
use Curly\Parser\TokenInterface;

/**
 * The SubparserInterface parses a stream of tokens into one or more nodes which are part of an abstract syntax tree.
 *
 * A subparser only creates nodes from a small portion of the token stream. A parser may temporarily delegate the
 * parsing process to a subparser which is capable of parsing a specific collection of tokens.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface SubparserInterface
{    
    /**
     * Creates part of the abstract syntax tree by parsing a collection of tokens.
     *
     * @param ParserInterface $parser the template parser.
     * @param TokenStream the stream of tokens to parse.
     * @return NodeInterface|null a node or null if not enough tokens have been parsed yet.
     */
    public function parse(ParserInterface $parser, TokenStream $stream);
}
