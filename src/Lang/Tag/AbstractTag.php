<?php 

namespace Curly\Lang\Tag;

use Curly\Ast\Node\TagNode;
use Curly\Collection\Stream\TokenStream;
use Curly\ParserInterface;
use Curly\Parser\Token;
use Curly\SubparserInterface;

/**
 *
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractTag implements SubparserInterface
{
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->current();
    
        $stream->expects(Token::T_IDENTIFIER);
        $stream->expects(Token::T_OPEN_PARENTHESIS);

        $args = array();
        if (!$stream->matches(Token::T_CLOSE_PARENTHESIS)) {
            do {
                $args[] = $parser->parseExpression($stream);
                
            } while ($stream->consumeIf(Token::T_COMMA));
        }

        $stream->expects(Token::T_CLOSE_PARENTHESIS);

        return new TagNode($this, $args, $token->getLineNumber());
    }
}
