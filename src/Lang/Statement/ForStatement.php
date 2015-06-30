<?php

namespace Curly\Lang\Statement;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ForStatement extends AbstractStatement
{
    /**
     * {@inheritDoc}
     */
    public function getKeyword()
    {
        return 'for';
    }
    
    /**
     * {@inheritDoc}
     */
    protected function getParser()
    {
    
    }
}
