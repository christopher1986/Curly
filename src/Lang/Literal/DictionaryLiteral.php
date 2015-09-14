<?php

namespace Curly\Lang\Literal;

use Curly\Ast\Node\EntryNode;
use Curly\Ast\Node\Expression\DictionaryNode;
use Curly\Collection\Stream\TokenStream;
use Curly\Lang\LiteralInterface;
use Curly\ParserInterface;
use Curly\Parser\Token;
use Curly\Parser\Exception\SyntaxException;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class DictionaryLiteral implements LiteralInterface
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
        return Token::T_OPEN_BRACE;
    }
    
    /**
     * {@inheritDoc}
     */
    public function parse(ParserInterface $parser, TokenStream $stream)
    {        
        $token  = $stream->expects(Token::T_OPEN_BRACE);
        
        $entries = array();        
        while (!$stream->matches(Token::T_CLOSE_BRACE)) {
            $entries[] = $this->parseEntry($parser, $stream);

            if (!$stream->matches(Token::T_COMMA, Token::T_CLOSE_BRACE)) {
                throw new SyntaxException(sprintf('Expected (",", "}"); received "%s"', $stream->current()->getValue()), $stream->current()->getLineNumber());
            }
            $stream->consumeIf(Token::T_COMMA);
        }
        
        // Consume the close brace.
        $stream->consume();
        
        return new DictionaryNode($entries, $token->getLineNumber());
    }
    
    /**
     * Parse a single dictionary entry.
     *
     * @param ParserInterface $parser the template parser.
     * @param TokenStream the stream of tokens to parse.
     * @return EntryNode an entry node.
     */
    private function parseEntry(ParserInterface $parser, TokenStream $stream)
    {
        if (!$stream->valid()) {
            throw new SyntaxException('Unexpected end of file.');   
        } else if (!$stream->matches(Token::T_FLOAT, Token::T_IDENTIFIER, Token::T_INTEGER, Token::T_OPEN_PARENTHESIS, Token::T_STRING)) {
            throw new SyntaxException(sprintf('Cannot find symbol "%s".', $stream->current()->getValue()), $stream->current()->getLineNumber());
        }
        
        $key = $parser->parseExpression($stream);
        
        $stream->expects(Token::T_COLON);

        return new EntryNode($key, $parser->parseExpression($stream));
    }
}
