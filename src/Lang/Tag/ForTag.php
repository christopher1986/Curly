<?php

namespace Curly\Lang\Tag;

use Curly\Ast\Node\ForNode;
use Curly\Ast\Node\Expression\VariableNode;
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
class ForTag extends AbstractTag
{    
    /**
     * {@inheritDoc}
     */
    public function getTags()
    {
        return array('for', 'endfor');
    }
        
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->current();
        
        $stream->expects(sprintf('%s:for', Token::T_KEYWORD));
        $stream->expects(Token::T_OPEN_PARENTHESIS);
        
        $loopVars = $this->parseVariables($parser, $stream);

        $stream->expects(sprintf('%s:in', Token::T_OPERATOR));
        
        $sequence = $parser->parseExpression($stream);
        
        $stream->expects(Token::T_CLOSE_PARENTHESIS);
        $stream->expects(Token::T_COLON);
        
        $children = $parser->parse($stream, sprintf('%s:endfor', Token::T_KEYWORD));
        
        $stream->expects(sprintf('%s:endfor', Token::T_KEYWORD));
        $stream->expects(Token::T_SEMICOLON, Token::T_CLOSE_TAG);
        
        return new ForNode($loopVars, $sequence, $children, $token->getLineNumber());
    }
    
    /**
     * Returns a collection of {@link VariableNode} objects.
     *
     * @param ParserInterface $parser the template parser.
     * @param TokenStream the stream of tokens to parse.
     * @return array a collection of {@link VariableNode} objects.
     */
    private function parseVariables(ParserInterface $parser, TokenStream $stream)
    {        
        $hasKey = false;
        $nodes  = array();
        while (!$stream->matches(Token::T_OPERATOR)) {
            $token   = $stream->expects(Token::T_IDENTIFIER);
            $nodes[] = new VariableNode($token->getValue(), $token->getLineNumber());
            
            if ($hasKey === false && ($hasKey = $stream->matches(Token::T_COMMA))) {
                $stream->consume();
            }
        }
        
        return $nodes;
    }
}
