<?php 

namespace Curly\Lang\Statement;

abstract class CompoundStatement extends AbstractStatement
{
    /**
     * {@inheritDoc}
     */
    public function isCompound()
    {
        return true;
    }
}
