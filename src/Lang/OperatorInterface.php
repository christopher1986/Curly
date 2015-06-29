<?php

namespace Curly\Lang;

use Curly\Collection\Comparator\Comparable;

interface OperatorInterface extends Comparable
{
    /**
     * An operator that is grouped from left to right.
     *
     * @var string
     */
    const ASSOCIATIVITY_LEFT_TO_RIGHT = 'ltr';
    
    /**
     * An operator that is grouped from right to left.
     *
     * @var string
     */
    const ASSOCIATIVITY_RIGHT_TO_LEFT = 'rtl';

    /**
     * An operator that has no associativity.
     *
     * @var string
     */
    const ASSOCIATIVITY_NONE = 'none';

    /**
     * Returns the operator phrase or symbol.
     *
     * @return string a phrase or symbol.
     */
    public function getOperator();
    
    /**
     * Returns the operator precedence.
     *
     * @return int the precedence.
     */
    public function getPrecedence();
    
    /**
     * Returns the operator associativity.
     *
     * @return string the associativity.
     */
    public function getAssociativity();
}
