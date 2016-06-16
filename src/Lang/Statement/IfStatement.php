<?php

namespace Curly\Lang\Statement;

use Curly\Ast\Node\Conditional;
use Curly\Ast\Node\IfStatement as IfStatementNode;
use Curly\ParserInterface;
use Curly\Parser\Stream\TokenStream;
use Curly\Parser\Token;
use Curly\Lang\StatementInterface;

/**
 * Creates an {@link IfStatementNode} instance for conditional statements.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class IfStatement implements StatementInterface
{        
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {    
        $startToken = $stream->current();
        $conditions = $this->parseConditions($parser, $stream);
        
        if ($stream->matches(sprintf('%s:else', Token::T_IDENTIFIER))) {
            $token = $stream->current();
            
            $stream->consume();
            $stream->expects(Token::T_COLON);
            
            $children = $parser->parse($stream, sprintf('%s:endif', Token::T_IDENTIFIER));
            $conditions[] = new Conditional(null, $children, $token->getLineNumber());
        }
        
        $stream->expects(sprintf('%s:endif', Token::T_IDENTIFIER));
        $stream->expects(Token::T_SEMICOLON, Token::T_CLOSE_TAG);
        
        return new IfStatementNode($conditions, $startToken->getLineNumber());
    }
    
    /**
     * Returns a collection of {@link Conditional} instances.
     *
     * @param ParserInterface $parser the template parser.
     * @param TokenStream the stream of tokens to parse.
     * @return array a collection of {@link Conditional} instances.
     */
    private function parseConditions(ParserInterface $parser, TokenStream $stream)
    {
        $types = array(
            sprintf('%s:else', Token::T_IDENTIFIER), 
            sprintf('%s:endif', Token::T_IDENTIFIER), 
        );
        
        $conditions = array();
        while (!$stream->matches($types)) {
            $token = $stream->current();
        
            $stream->expects(sprintf('%s:if', Token::T_IDENTIFIER), sprintf('%s:elseif', Token::T_IDENTIFIER));
            $stream->expects(Token::T_OPEN_PARENTHESIS);
            
            $expression = $parser->parseExpression($stream);

            $stream->expects(Token::T_CLOSE_PARENTHESIS);
            $stream->expects(Token::T_COLON);
            
            $children = $parser->parse($stream, array(
                sprintf('%s:elseif', Token::T_IDENTIFIER), 
                sprintf('%s:else', Token::T_IDENTIFIER), 
                sprintf('%s:endif', Token::T_IDENTIFIER),
            ));
            
            $conditions[] = new Conditional($expression, $children, $token->getLineNumber());
        }
        
        return $conditions;
    }
}
