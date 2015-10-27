<?php 

namespace Curly\Lang\Tag;

use Curly\Ast\Node\CallableNode;
use Curly\Collection\Stream\TokenStream;
use Curly\Lang\TagInterface;
use Curly\ParserInterface;
use Curly\Parser\Token;

/**
 *
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractTag implements TagInterface
{
    /**
     * The method to invoke for this tag.
     *
     * @param array|null $args (optional) additional arguments for this tag.
     * @return mixed|null a possible return value of this tag.
     */
    abstract public function call(array $args = null);

    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {
        $token = $stream->current();
    
        $stream->expects(Token::T_IDENTIFIER);
        $stream->expects(Token::T_OPEN_PARENTHESIS);
        
        $args = array();
        if ($stream->consumeIf(Token::T_OPEN_PARENTHESIS)) {
            do {
                $args[] = $parser->parseExpression($stream);
            } while ($stream->consumeIf(Token::T_COMMA));
           
            $stream->expects(Token::T_CLOSE_PARENTHESIS);
        }
        
        return new CallableNode($this, $args, $token->getLineNumber());
    }
    
    /**
     * Invoke this {@link TagInterface} instance as a callback function.
     *
     * @link http://php.net/manual/en/language.oop5.magic.php#object.invoke Call an object as a function
     */
    public function __invoke()
    {
        $this->call(func_get_args());
    }
}
