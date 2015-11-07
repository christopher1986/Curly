<?php

namespace Curly\Lang\Literal;

use Curly\Ast\Node\Expression\LiteralNode;
use Curly\Lang\LiteralInterface;
use Curly\ParserInterface;
use Curly\Parser\Stream\TokenStream;
use Curly\Parser\Token;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class NullLiteral implements LiteralInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return Token::T_NULL;
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->consume();
        return new LiteralNode(null, $token->getLineNumber());
    }
}
