<?php

namespace Curly\Lang\Statement;

use Curly\Lang\StatementInterface;

abstract class AbstractStatement implements StatementInterface
{ 
    /**
     * {@inheritDoc}
     */
    public function isConditional()
    {
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isCompound()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function compareTo($obj)
    {
        if ($obj instanceof self) {
            if (strlen($this->getKeyword()) == strlen($obj->getKeyword())) {
                return 0;
            }
            return (strlen($this->getKeyword()) > strlen($obj->getKeyword())) ? 1 : -1;
        }
        return 0;
    }
}
