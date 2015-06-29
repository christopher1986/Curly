<?php

namespace Curly\Lang\Operator\Binary;

use Curly\Lang\Operator\AbstractOperator;

class GreaterOperator extends AbstractOperator
{
    /**
     * {@inheritDoc}
     */
    public function getOperator()
    {
        return '>';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPrecedence()
    {
        return  ;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAssociativity()
    {
        return self::ASSOCIATIVITY_LEFT_TO_RIGHT;
    }
}
