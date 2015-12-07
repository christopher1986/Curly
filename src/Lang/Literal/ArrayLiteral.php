<?php

namespace Curly\Lang\Literal;

use Curly\Ast\Node\Expression\ArrayLiteral as ArrayLiteralNode;
use Curly\Lang\LiteralInterface;
use Curly\ParserInterface;
use Curly\Parser\Stream\TokenStream;
use Curly\Parser\Token;
use Curly\Parser\Exception\SyntaxException;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class ArrayLiteral implements LiteralInterface
{
    /**
     * A parser.
     *
     * @var ParserInterface
     */
    protected $parser;

    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return Token::T_OPEN_BRACKET;
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        // Consule open bracket.
        $token = $stream->consume();

        $entries = array();
        while (!$stream->matches(Token::T_CLOSE_BRACKET)) {
            $entries[] = $parser->parseExpression($stream);
            
            if (!$stream->matches(Token::T_COMMA, Token::T_CLOSE_BRACKET)) {
                throw new SyntaxException(sprintf('Expected (",", "]"); received "%s"', $stream->current()->getValue()), $stream->current()->getLineNumber());
            }
            $stream->consumeIf(Token::T_COMMA);
        }
        
        // Consume close bracket.
        $stream->consume();

        return new ArrayLiteralNode($entries, $token->getLineNumber(), ArrayLiteralNode::TYPE_NUMERIC);
    }
}
