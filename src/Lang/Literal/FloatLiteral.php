<?php

namespace Curly\Lang\Literal;

use Curly\Ast\Node\Expression\ScalarNode;
use Curly\Collection\Stream\TokenStream;
use Curly\Lang\LiteralInterface;
use Curly\ParserInterface;
use Curly\Parser\Token;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class FloatLiteral implements LiteralInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return Token::T_FLOAT;
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->consume();
        return new ScalarNode((float) $token->getValue(), $token->getLineNumber(), ScalarNode::TYPE_FLOAT);
    }
}
