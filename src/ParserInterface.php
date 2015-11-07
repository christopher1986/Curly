<?php

namespace Curly;

use Curly\Parser\Stream\TokenStream;
use Curly\Parser\TokenInterface;

/**
 * The ParserInterface parses a collection of tokens into an abstract syntax tree.
 *
 * A class that implements this interface only performs syntactical analysis on a stream of tokens. These tokens
 * were created through a process that takes places during the lexical analysis. After the lexical analysis a parser
 * is responsible for parsing these tokens into an abstract syntax tree.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface ParserInterface extends EngineCapableInterface, LibraryCapableInterface
{
    /**
     * Creates an abstract syntax tree by parsing tokens from the specified stream.
     *
     * The second argument provides the capability to parse the specified stream until
     * one of the matching tokens has been found. The syntax of this argument is
     * identical to that of the {@link TokenStream::matches($types) method.
     *
     * @param TokenStream a stream of tokens to parse.
     * @param string|string[]|null $until (optional) one or more possible token types to match.
     * @return NodeInterface|null an abstract syntax tree.
     */
    public function parse(TokenStream $stream, $until = null);
    
    /**
     * Parse a single expression.
     *
     * @param TokenStream a stream of tokens to parse.
     * @param int $precedence (optional) the operator precedence that when exceeded by an operator will add
     *                                   that operator as a child node to the previous node.
     * @return NodeInterface an expression node.
     */
    public function parseExpression(TokenStream $stream, $precedence = 0);
    
    /**
     * Parse a primary expression.
     *
     * @param TokenStream a stream of tokens to parse.
     * @return NodeInterface an expression node.
     */
    public function parsePrimaryExpression(TokenStream $stream);
    
    /**
     * Returns a stream containing tokens.
     *
     * @return TokenStream a stream containing tokens.
     */
    public function getStream();
}
