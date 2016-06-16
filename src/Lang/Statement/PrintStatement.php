<?php

namespace Curly\Lang\Statement;

use Curly\Ast\Node\PrintStatement as PrintStatementNode;
use Curly\ParserInterface;
use Curly\Parser\Stream\TokenStream;
use Curly\Parser\Token;
use Curly\Lang\StatementInterface;

/**
 * Creates a {@link PrintStatementNode} instance that displays the result of an expression.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class PrintStatement implements StatementInterface
{    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->current();
        
        $stream->expects(sprintf('%s:print', Token::T_IDENTIFIER));

        $expression = $parser->parseExpression($stream);
        
        $stream->expects(Token::T_SEMICOLON, Token::T_CLOSE_TAG);
        
        return new PrintStatementNode($expression, $token->getLineNumber());
    }
}
