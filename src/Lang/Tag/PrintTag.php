<?php

namespace Curly\Lang\Tag;

use Curly\Collection\Stream\TokenStream;
use Curly\Lang\TagInterface;
use Curly\ParserInterface;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class PrintTag implements TagInterface
{    
    /**
     * {@inheritDoc}
     */    
    public function getTag()
    {
        return 'print';
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        
    }
}
