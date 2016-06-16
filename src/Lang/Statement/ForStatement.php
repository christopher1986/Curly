<?php

namespace Curly\Lang\Statement;

use Curly\Ast\Node\ForStatement as ForStatementNode;
use Curly\Ast\Node\Expression\Variable;
use Curly\Parser\Stream\TokenStream;
use Curly\ParserInterface;
use Curly\Parser\Token;
use Curly\Lang\StatementInterface;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ForStatement implements StatementInterface
{        
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->current();
      
        $stream->expects(sprintf('%s:for', Token::T_IDENTIFIER));
        $stream->expects(Token::T_OPEN_PARENTHESIS);
        
        $loopVars = $this->parseVariables($parser, $stream);
        
        $stream->expects(sprintf('%s:in', Token::T_OPERATOR));
        
        $sequence = $parser->parseExpression($stream);
        
        $stream->expects(Token::T_CLOSE_PARENTHESIS);
        $stream->expects(Token::T_COLON);

        $children = $parser->parse($stream, array(sprintf('%s:endfor', Token::T_IDENTIFIER)));

        $stream->expects(sprintf('%s:endfor', Token::T_IDENTIFIER));
        $stream->expects(Token::T_SEMICOLON, Token::T_CLOSE_TAG);
        
        return new ForStatementNode($loopVars, $sequence, $children, $token->getLineNumber());
    }
    
    /**
     * Returns a collection of {@link Variable} instances.
     *
     * @param ParserInterface $parser the template parser.
     * @param TokenStream the stream of tokens to parse.
     * @return array a collection of {@link Variable} instances.
     */
    private function parseVariables(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->expects(Token::T_VARIABLE);
        $variables   = array();
        $variables[] = new Variable($token->getValue(), $token->getLineNumber());
            
        if ($stream->consumeIf(Token::T_COMMA)) {
            $token = $stream->expects(Token::T_VARIABLE);
            $variables[] = new Variable($token->getValue(), $token->getLineNumber());
        }
        
        return $variables;
    }
}
