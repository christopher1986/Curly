<?php

namespace Curly\Lang\Tag;

use Curly\Collection\Stream\TokenStream;
use Curly\ParserInterface;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class AssignmentTag extends AbstractTag
{    
    /**
     * {@inheritDoc}
     */
    public function getTags()
    {
        return array('=');
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        
    }
}
