<?php

namespace Curly;

use Curly\Collection\Map;
use Curly\Lang\LiteralInterface;
use Curly\Lang\OperatorInterface;
use Curly\Lang\Operator\AbstractBinaryOperator;
use Curly\Lang\TagInterface;

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
        $this->filters         = new Map();
        $this->tags            = new Map();
        $this->literals        = new Map();
        $this->unaryOperators  = new Map();
        $this->binaryOperators = new Map();
    }

    /**
     * {@inheritDoc}
     */
    public function registerFilter($name, $filter)
    {        
        $key = (is_string($name)) ? $this->normalize($name) : $name;
        $this->filters->add($key, $tag);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFilter($name)
    {        
        $key = (is_string($name)) ? $this->normalize($name) : $name;
        return $this->filters->get($key);
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
        $key = (is_string($name)) ? $this->normalize($name) : $name;
        $this->tags->add($key, $tag);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTag($name)
    {        
        $key = (is_string($name)) ? $this->normalize($name) : $name;
        return $this->tags->get($key);
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
        $key = (is_string($type)) ? $this->normalize($type) : $type;
        $this->literals->add($key, $literal);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLiteral($type)
    {        
        $key = (is_string($type)) ? $this->normalize($type) : $type;
        return $this->literals->get($key);
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
            $key = (is_string($name)) ? $this->normalize($name) : $name;
            $this->binaryOperators->add($key, $operator);
        } else {
            $key = (is_string($name)) ? $this->normalize($name) : $name;
            $this->unaryOperators->add($key, $operator);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUnaryOperator($name)
    {        
        $key = (is_string($name)) ? $this->normalize($name) : $name;
        return $this->unaryOperators->get($key);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getBinaryOperator($name)
    {
        $key = (is_string($name)) ? $this->normalize($name) : $name;
        return $this->binaryOperators->get($key);
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
    
    /**
     * Returns a lowercase string and replaces whitespace with hyphens.
     *
     * @param string $name the string to normalize.
     * @return string a normalized string.
     * @throws InvalidArgumentException if the first argument is not a string or the string is empty.
     */
    private function normalize($name)
    {
        if (!is_string($name) || strlen($name) === 0) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a non-empty string argument; received "%s" instead',
                __METHOD__,
                (is_object($name) ? get_class($name) : gettype($name))
            ));
        }
        
        return preg_replace('/(\s+)/', '-', strtolower(trim($name)));
    }
}
