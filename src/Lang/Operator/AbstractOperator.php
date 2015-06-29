<?php

namespace Curly\Lang\Operator;

use Curly\Lang\OperatorInterface;

abstract class AbstractOperator implements OperatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function compareTo($obj)
    {
        if ($obj instanceof self) {
            if (strlen($this->getPrecedence()) == strlen($obj->getPrecedence())) {
                return 0;
            }
            return (strlen($this->getPrecedence()) > strlen($obj->getPrecedence())) ? 1 : -1;
        }
        return 0;
    }
}
