<?php

namespace Curly\Lang;

use Curly\ParserInterface;
use Curly\Collection\Stream\TokenStream;

/**
 * The TagInterface allows a tag to parse a stream of tokens into one or more nodes which 
 * are part of the tag.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface TagInterface
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
