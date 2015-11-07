<?php

namespace Curly\Lang;

use Curly\ParserInterface;
use Curly\Parser\Stream\TokenStream;

/**
 * The StatementInterface allows a statement to parse a stream of tokens into one or more
 * nodes which are part of the statement.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface StatementInterface
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
