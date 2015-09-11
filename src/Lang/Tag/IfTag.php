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
class IfTag extends AbstractTag
{    
    /**
     * {@inheritDoc}
     */
    public function getTags()
    {
        return array('if', 'elseif', 'else', 'endif');
    }
        
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->current();
        
        $stream->expects(sprintf('%s:if', Token::T_KEYWORD));
        $stream->expects(Token::T_OPEN_PARENTHESIS);
        
        $expression = $parser->parseExpression($stream);
        
        $stream->expects(Token::T_CLOSE_PARENTHESIS);
        $stream->expects(Token::T_COLON);
        
        $children = $parser->parse($stream, array(
            sprintf('%s:elseif', Token::T_KEYWORD),
            sprintf('%s:else', Token::T_KEYWORD),
            sprintf('%s:endif', Token::T_KEYWORD)
        ));
        
        var_dump($children);
        exit;
    }
}
