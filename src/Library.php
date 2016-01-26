<?php

namespace Curly;

use Curly\Lang\LiteralInterface;
use Curly\Lang\OperatorInterface;
use Curly\Lang\StatementInterface;
use Curly\Lang\TagInterface;
use Curly\Lang\Operator\AbstractBinaryOperator;

use Webwijs\Collection\Map;

/** 
 *
 * 
 * @author Chris Harris 
 * @version 1.0.0
 * @since 1.0.0
 */
class Library implements LibraryInterface
{
    /**
     * A mapping between names and statements.
     *
     * @var MapInterface
     */
    private $statements;

    /**
     * A mapping between names and filters.
     *
     * @var MapInterface
     */
    private $filters;

    /**
     * A mapping between names and tags.
     *
     * @var MapInterface
     */
    private $tags;
    
    /**
     * A mapping between names and unary operators.
     *
     * @var MapInterface
     */
    private $unaryOperators;

    /**
     * A mapping between names and binary operators.
     *
     * @var MapInterface
     */
    private $binaryOperators;
    
    /**
     * A mapping between names and literals.
     *
     * @var MapInterface
     */
    private $literals;

    /**
     * Construct a new Library.
     */
    public function __construct()
    {
        $this->statements      = new Map();
        $this->filters         = new Map();
        $this->tags            = new Map();
        $this->literals        = new Map();
        $this->unaryOperators  = new Map();
        $this->binaryOperators = new Map();
    }
    
    /**
     * {@inheritDoc}
     */
    public function registerStatement($name, StatementInterface $statement)
    {
        $this->statements->add($name, $statement);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getStatement($name)
    {
        return $this->statements->get($name);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getStatements()
    {
        return $this->statements->values();
    }

    /**
     * {@inheritDoc}
     */
    public function registerFilter($name, $filter)
    {
        $this->filters->add($name, $filter);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFilter($name)
    {
        return $this->filters->get($name);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return $this->filters->values();
    }
    
    /**
     * {@inheritDoc}
     */
    public function registerTag($name, TagInterface $tag)
    {
        $this->tags->add($name, $tag);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTag($name)
    {
        return $this->tags->get($name);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTags()
    {
        return $this->tags->values();
    }
    
    /**
     * {@inheritDoc}
     */
    public function registerLiteral($type, LiteralInterface $literal)
    {
        $this->literals->add($type, $literal);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLiteral($type)
    {
        return $this->literals->get($type);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLiterals()
    {
        return $this->literals->values();
    }
    
    /**
     * {@inheritDoc}
     */
    public function registerOperator($name, OperatorInterface $operator)
    {        
        if ($operator instanceof AbstractBinaryOperator) {
            $this->binaryOperators->add($name, $operator);
        } else {
            $this->unaryOperators->add($name, $operator);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUnaryOperator($name)
    {        
        return $this->unaryOperators->get($name);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getBinaryOperator($name)
    {
        return $this->binaryOperators->get($name);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUnaryOperators()
    {
        return $this->unaryOperators->values();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getBinaryOperators()
    {
        return $this->binaryOperators->values();
    }
}
