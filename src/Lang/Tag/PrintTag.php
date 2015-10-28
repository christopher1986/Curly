<?php

namespace Curly\Lang\Tag;

use Curly\Ast\Node\PrintNode;
use Curly\Collection\Stream\TokenStream;
use Curly\ParserInterface;
use Curly\Parser\Token;
use Curly\SubparserInterface;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class PrintTag implements SubparserInterface
{    
    /**
     * {@inheritDoc}
     */    
    public function getName()
    {
        return 'print';
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->current();
        
        $stream->expects(sprintf('%s:print', Token::T_IDENTIFIER));

        $children = $parser->parseExpression($stream);
        
        $stream->expects(Token::T_SEMICOLON, Token::T_CLOSE_TAG);
        
        return new PrintNode(array($children), $token->getLineNumber());
    }
}
