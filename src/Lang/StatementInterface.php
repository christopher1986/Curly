<?php

namespace Curly\Lang;

use SplStack;

use Curly\Collection\Comparator\Comparable;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface StatementInterface extends Comparable
{
    /**
     * Returns the keyword associated with this statement.
     *
     * @return string the keyword.
     */
    public function getKeyword();
    
    /**
     * Parses the given tokens into an abstract syntax tree (AST).
     *
     * @param SplStack $token a last-in-first-out (LIFO) stack of tokens.
     * @return NodeInterface|null a node from the specified tokens, or null if parsing failed.
     */
    public function parse(SplStack $tokens);
}
