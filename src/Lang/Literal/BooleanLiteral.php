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
class BooleanLiteral implements LiteralInterface
{
    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return Token::T_BOOLEAN;
    }
    
    /**
     * {@inheritDoc}
     *
     * @link http://stackoverflow.com/questions/7336861/how-to-convert-string-to-boolean-php#answer-15075609
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->consume();
        $value = filter_var($token->getValue(), FILTER_VALIDATE_BOOLEAN);
        return new ScalarNode($value, $token->getLineNumber(), ScalarNode::TYPE_BOOLEAN);
    }
}
