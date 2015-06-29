<?php

namespace Curly\Lang;

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
     * Returns true if this statement is a conditional statement.
     *
     * @return bool true is this statement is conditional, false otherwise.
     * @link http://cpp-wiki.wikidot.com/tutorials:conditional-statements
     */
    public function isConditional();
    
    /**
     * Returns true if this statement is a compound statement.
     *
     * @return bool true if this statement is a compound, false otherwise.
     * @link http://www.macs.hw.ac.uk/~rjp/Coursewww/CPPwww/compound.html
     */
    public function isCompound();
    
    /**
     * Parses the given tokens into an abstract syntax tree (AST).
     *
     * @return NodeInterface tree of nodes
     */
    public function parse();
}
