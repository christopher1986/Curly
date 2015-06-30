<?php

namespace Curly\Lang\Statement;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class AssignmentStatement extends AbstractStatement
{
    /**
     * {@inheritDoc}
     */
    public function getKeyword()
    {
        return '=';
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getParser()
    {
    
    }
}
