<?php

namespace Curly\Lang\Literal;

use Curly\Ast\Node\Expression\ArrayNode;
use Curly\Collection\Stream\TokenStream;
use Curly\Lang\LiteralInterface;
use Curly\Parser\Token;
use Curly\ParserInterface;

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
        $token  = $stream->expects(Token::T_OPEN_BRACKET);

        $items = array();
        while (!$stream->matches(Token::T_CLOSE_BRACKET)) {
            $items[] = $parser->parseExpression($stream);

            if (!$stream->matches(Token::T_COMMA, Token::T_CLOSE_BRACKET)) {
                throw new SyntaxException(sprintf('Expected (",", "]"); received "%s"', $stream->current()->getValue()), $stream->current()->getLineNumber());
            }
            $stream->consumeIf(Token::T_COMMA);
        }
        
        // Consume the close bracket.
        $stream->consume();

        return new ArrayNode($items, $token->getLineNumber());
    }
}
