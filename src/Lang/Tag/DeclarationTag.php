<?php

namespace Curly\Lang\Tag;

use Curly\Collection\Stream\TokenStream;
use Curly\ParserInterface;
use Curly\Parser\Token;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class DeclarationTag extends AbstractTag
{    
    /**
     * {@inheritDoc}
     */
    public function getTags()
    {
        return array('var');
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = new Token(';', Token::T_SEMICOLON);
        $nodes = $parser->parseUntil($token);
        
        var_dump($nodes);
    }
}
