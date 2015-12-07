<?php

namespace Curly;

use Curly\Lang\LiteralInterface;
use Curly\Lang\OperatorInterface;
use Curly\Lang\StatementInterface;
use Curly\Lang\TagInterface;

/** 
 * The LibraryInterface contains a collection of language constructs that can be accessed and used by
 * the lexer during the lexical analysis or by the parser for syntactical analysis.
 * 
 * @author Chris Harris 
 * @version 1.0.0
 * @since 1.0.0
 */
interface LibraryInterface
{
    /**
     * Add a new statement with the specified name.
     *
     * @param mixed $name the name associated with the statement.
     * @param StatementInterface $statement the statement to add.
     */
    public function registerStatement($name, StatementInterface $statement);
    
    /**
     * Returns if present a statement for the specified name.
     *
     * @param mixed $name the name whose associated statement is to be returned.
     * @return object|null a statement for the specified name, or null on failure.
     */
    public function getStatement($name);
    
    /**
     * Returns a collection of registered statements.
     *
     * @return array collection of statements.
     */
    public function getStatements();

    /**
     * Add a new filter with the specified name.
     *
     * @param mixed $name the name associated with the filter.
     * @param object $filter the filter to add.
     */
    public function registerFilter($name, $filter);
    
    /**
     * Returns if present a filter for the specified name.
     *
     * @param mixed $name the name whose associated filter is to be returned.
     * @return object|null a filter for the specified name, or null on failure.
     */
    public function getFilter($name);
    
    /**
     * Returns a collection of registered filters.
     *
     * @return array collection of filters.
     */
    public function getFilters();
    
    /**
     * Add a new tag with the specified name.
     *
     * @param mixed $name the name associated with the tag.
     * @param TagInterface $tag the tag to add.
     */
    public function registerTag($name, TagInterface $tag);
    
    /**
     * Returns if present a tag for the specified name.
     *
     * @param mixed $name the name whose associated tag is to be returned.
     * @return object|null a tag for the specified name, or null on failure.
     */
    public function getTag($name);
    
    /**
     * Returns a collection of registered tags.
     *
     * @return array collection of tags.
     */
    public function getTags();
    
    /**
     * Add a new literal for the specified token type.
     *
     * @param mixed $type the token type associated with the literal.
     * @param LiteralInterface $literal the literal to add.
     */
    public function registerLiteral($name, LiteralInterface $literal);
    
    /**
     * Returns if present a literal for the specified token type.
     *
     * @param mixed $type the token type for which a literal is to be returned
     * @return LiteralInterface|null a literal for the specified token type, or null on failure.
     */
    public function getLiteral($type);
    
    /**
     * Returns a collection of registered literals.
     *
     * @return ListInterface collection of literals.
     */
    public function getLiterals();
    
    /**
     * Add a new operator with the specified name.
     *
     * @param mixed $name the name associated with the operator.
     * @param OperatorInterface $operator the operator to add.
     */
    public function registerOperator($name, OperatorInterface $operator);
    
    /**
     * Returns if present a unary operator for the specified name.
     *
     * @param mixed $name the name whose associated operator is to be returned.
     * @return OperatorInterface|null a unary operator for the specified name, or null on failure.
     */
    public function getUnaryOperator($name);
    
    /**
     * Returns if present a binary operator for the specified name.
     *
     * @param mixed $name the name whose associated operator is to be returned.
     * @return OperatorInterface|null a binary operator for the specified name, or null on failure.
     */
    public function getBinaryOperator($name);
    
    /**
     * Returns a collection of registered unary operators.
     *
     * @return array collection of unary operators.
     */
    public function getUnaryOperators();
    
    /**
     * Returns a collection of registered binary operators.
     *
     * @return array collection of binary operators.
     */
    public function getBinaryOperators();
}


