<?php

namespace Curly;

use Curly\Common\Comparator\LengthComparator;
use Curly\Io\StringReader;
use Curly\Parser\Exception\SyntaxException;
use Curly\Parser\Stream\Stream;
use Curly\Parser\Stream\TokenStream;
use Curly\Parser\Token;
use Curly\Util\Arrays;

/**
 * The Lexer is a concrete implementation of the {@link LexerInterface}.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class Lexer implements LexerInterface
{
    /**
     * Lexer states
     */
    const STATE_TEXT = 1;
    const STATE_LANG = 2;

    /**
     * A collection of code tags to match.
     *
     * @var array
     */
    private $tags = array(
        'open_tag'        => '{%',
        'close_tag'       => '%}',
        'open_print_tag'  => '{{',
        'close_print_tag' => '}}',
    );
    
    /**
     * A collection which maps symbols to token types.
     *
     * @var array
     */
    private $punctuations = array(
        '[' => Token::T_OPEN_BRACKET,
        ']' => Token::T_CLOSE_BRACKET,
        '(' => Token::T_OPEN_PARENTHESIS,
        ')' => Token::T_CLOSE_PARENTHESIS,
        '{' => Token::T_OPEN_BRACE,
        '}' => Token::T_CLOSE_BRACE,
        '.' => Token::T_PERIOD,
        ',' => Token::T_COMMA,
        '|' => Token::T_PIPELINE,
        ';' => Token::T_SEMICOLON,
        ':' => Token::T_COLON,
        '=' => Token::T_ASSIGN,
    );

    /**
     * A collection of tokens found by the lexer.
     *
     * @var array
     */
    private $tokens = array();

    /**
     * The template engine.
     *
     * @var EngineInterface
     */
    private $engine;
    
    /**
     * A reader from which to read characters.
     *
     * @var StringReader
     */
    private $reader;

    /**
     * A collection of start tags and their positions within the input string.
     *
     * @var array
     */
    private $tagPositions = array();

    /**
     * The state the lexer is curently in. 
     *
     * @var int
     */
    private $state = self::STATE_TEXT;

    /**
     * A collection of patterns to match.
     *
     * @var array
     */
    private $regexes = array(
        'literal'    => '/(true|false|null)\b/Ai',
        'number'     => '/([0-9]+(?:\.[0-9]+)?)/A',
        'string'     => '/([\'"])(.*?)(?<!\\\)\1/As',
        'identifier' => '/([a-z_\x7f-\xff]{1}[a-z0-9_\x7f-\xff]*)/Ai',
        'variable'   => '/\$([a-z_\x7f-\xff]{1}[a-z0-9_\x7f-\xff]*)/Ai',
    );

    /**
     * Construct a new Lexer.
     *
     * @param EngineInterface $engine the template engine.
     */
    public function __construct(EngineInterface $engine)
    {
        $this->setEngine($engine);
    }

    /**
     * {@inheritDoc}
     */
    public function tokenize($input)
    {    
        $this->reset();
        $this->setInput($input);       
    
        while ($this->reader->hasNextChar()) {
            switch($this->state) {
                case self::STATE_LANG:
                    $this->tokenizeLang();
                    break;
                case self::STATE_TEXT:
                default:
                    $this->tokenizeText();
                    break;
            }
        }
                    
        return new TokenStream(new Stream($this->tokens));
    }
    
    /**
     * Tokenize text which does not belong to the language.
     *
     * The grammar associated with these symbols and words is shown below and is written
     * in Extended Backus–Naur Form (EBNF).
     *
     * text ::= {"0x20".."0x7e"}
     */
    private function tokenizeText()
    {            
        $reader = $this->reader;
        if (($tagPos = array_shift($this->tagPositions)) !== null) {
            if (($amount = $tagPos[1] - $reader->getPosition()) > 0) {
                $this->pushToken(Token::T_TEXT, $reader->readChar($amount), $reader->getLineNumber());
            }

            $tag = $reader->readChar(strlen($tagPos[0]));
            switch ($tag) {
                case $this->tags['open_tag']:
                    $this->pushToken(Token::T_OPEN_TAG, $tag, $reader->getLineNumber());
                    break;
                case $this->tags['open_print_tag']:
                    $this->pushToken(Token::T_OPEN_PRINT_TAG, $tag, $reader->getLineNumber());
                    break;
            }
            $this->state = self::STATE_LANG;

        } else if ($reader->hasNextChar()) {
            $this->pushToken(Token::T_TEXT, trim($reader->readToEnd(), "\n\r\0\x0B"), $reader->getLineNumber());
        }
    }
    
    /**
     * Tokenize symbols and words that are part of the language.
     *
     * The grammar associated with these symbols and words is shown below and is written
     * in Extended Backus–Naur Form (EBNF).
     * 
     * digit       ::= 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9
     * float       ::= digit "." digit {digit}
     * number      ::= digit | float
     * letter      ::= "a".."z" | "A".."Z" | "x7f".."xff"
     * identifier  ::= letter | "_" {letter | digit | "_"}
     * string      ::= ['"] text ['"]
     * boolean     ::= true | false
     * null        ::= null
     * symbols     ::= "+" | "-" | "*" | "/" | "%" | ">" | "<" | ">=" | "<=" | "==" | "!=" | "or" | "and" | "not"
     * punctuation ::= "[" | "]" | "(" | ")" | "{" | "}" | "." | "," | "|" | ";" | ":" | "="
     */
    private function tokenizeLang()
    {    
        // ignore whitespace.
        if ($this->reader->matches('/\s+/A', $matches)) {
            $this->reader->skip(strlen($matches[0]));
        }
    
        $reader  = $this->reader;
        $matches = array();
        // code tags
        if ($reader->matches($this->getTagRegex(), $matches)) {
            switch ($matches[1]) {
                case $this->tags['close_tag']:
                    $this->pushToken(Token::T_CLOSE_TAG, $matches[1], $reader->getLineNumber());
                    break;
                case $this->tags['close_print_tag']:
                    $this->pushToken(Token::T_CLOSE_PRINT_TAG, $matches[1], $reader->getLineNumber());
                    break;
                default:
                    throw new SyntaxException(sprintf('Unexpected "%s"', $matches[0]), $reader->getLineNumber());
                    break;
            }

            $this->state = self::STATE_TEXT;
        }
        // operator symbols
        else if ($reader->matches($this->getOperatorRegex(), $matches)) {
            $this->pushToken(Token::T_OPERATOR, $matches[1], $reader->getLineNumber());
        } 
        // strings
        else if ($reader->matches($this->regexes['string'], $matches)) {
            $this->pushToken(Token::T_STRING, stripslashes($matches[2]), $reader->getLineNumber());
        } 
        // literals
        else if ($reader->matches($this->regexes['literal'], $matches)) {
            if (in_array(strtolower($matches[1]), array('true', 'false'))) {
                $this->pushToken(Token::T_BOOLEAN, $matches[1], $reader->getLineNumber());
            } else {
                $this->pushToken(Token::T_NULL, $matches[1], $reader->getLineNumber());
            }
        } 
        // numbers
        else if ($reader->matches($this->regexes['number'], $matches)) {
            if (ctype_digit($matches[1]) && $matches[1] <= PHP_INT_MAX) {
                $this->pushToken(Token::T_INTEGER, $matches[1], $reader->getLineNumber());
            } else {
                $this->pushToken(Token::T_FLOAT, $matches[1], $reader->getLineNumber());
            }
        } 
        // variables
        else if ($reader->matches($this->regexes['variable'], $matches)) {
            $this->pushToken(Token::T_VARIABLE, $matches[1], $reader->getLineNumber());
        }
        // identifiers
        else if ($reader->matches($this->regexes['identifier'], $matches)) {
            $this->pushToken(Token::T_IDENTIFIER, $matches[1], $reader->getLineNumber());
        }
        // punctuation
        else if ($reader->matches($this->getPunctutationRegex(), $matches)) {
            $value = $matches[1];
            $type  = (isset($this->punctuations[$value])) ? $this->punctuations[$value] : Token::T_UNKNOWN;
                
            $this->pushToken($type, $value, $reader->getLineNumber());
        }

        if ($reader->hasNextChar()) {
            if (!isset($matches[0])) {
                throw new SyntaxException(sprintf('Unknown character "%s" was found', $reader->readWord()), $reader->getLineNumber());
            }
            
            // skip the whole sequence of characters that matched.
            $reader->skip(strlen($matches[0]));
        }
    }
    
    /**
     * Returns a regular expression to match operators from the template engine.
     *
     * @return string a regular expression to match operators.
     */
    private function getOperatorRegex()
    {
        if (!isset($this->regexes['symbols'])) {
            $symbols = $this->engine->getOperatorSymbols()->toArray();
            Arrays::sort($symbols, new LengthComparator());
          
            $patterns = array();
            foreach ($symbols as $symbol) {
                if (ctype_alpha($symbol)) {
                    $patterns[] = sprintf('%s\b', preg_quote($symbol, '/'));
                } else {
                    $patterns[] = preg_quote($symbol, '/');
                }
            }
            
            $this->regexes['symbols'] = sprintf('/(%s)/A', implode('|', $patterns));
        }

        return $this->regexes['symbols'];
    }
    
    /**
     * Returns a regular expression to match tags from the template engine.
     *
     * @return string a regular expression to match tags.
     */
    private function getTagRegex()
    {
        if (!isset($this->regexes['tag'])) {
            $patterns = array();
            foreach ($this->tags as $tag) {
                $patterns[] = preg_quote($tag, '/');
            }
                        
            $this->regexes['tag'] = sprintf('/(%s)\n?/A', implode('|', $patterns));
        }

        return $this->regexes['tag'];
    }
    
    /**
     * Returns a regular expression to match punctuation and special characters.
     *
     * @return string a regular expression to match punctuation and special characters.
     */
    private function getPunctutationRegex()
    {
        if (!isset($this->regexes['punctuation'])) {
            $patterns     = array();
            $punctuations = array_keys($this->punctuations);
            foreach ($punctuations as $punctuation) {
                $patterns[] = preg_quote($punctuation, '/');
            }
            
            $this->regexes['punctuation'] = sprintf('/(%s)/A', implode('|', $patterns));
        }
        
        return $this->regexes['punctuation'];
    }
    
    
    /**
     * Set the input data that the lexer will tokenize.
     *
     * @param string $input the data that will be tokenized.
     * @throws InvalidArgumentException if the specified argument is not a string.
     */
    private function setInput($input)
    {        
	    if (!is_string($input)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($input) ? get_class($input) : gettype($input))
            ));
	    }
    
        $normalized = str_replace(array("\r\n", "\r"), "\n", rtrim($input, "\r\n"));
        $regex = sprintf('/(%s|%s)/i', preg_quote($this->tags['open_tag'], '/'), preg_quote($this->tags['open_print_tag'], '/'));
        preg_match_all($regex, $normalized, $matches, PREG_OFFSET_CAPTURE);
        
        $this->tagPositions = $matches[0];
        $this->reader       = new StringReader($normalized); 
    }
    
    /**
     * Reset the lexer to it's starting state.
     *
     * @return void
     */
    private function reset()
    {
        $this->tagPositions = array();
        $this->tokens       = array();
    }
    
    /**
     * Set the template engine.
     *
     * @param EngineInterface the template engine.
     */
    private function setEngine(EngineInterface $engine)
    {
        $this->engine = $engine;
    }
    
    /**
     * Push a new token onto a collection of tokens.
     *
     * @param mixed $type The token type.
     * @param mixed $value The value for this token.
     * @param int $lineNumber (optional) the line number of the value.
     */
    private function pushToken($type, $value = '', $lineNumber = -1)
    {
        $this->tokens[] = new Token($type, $value, $lineNumber);
    }
}
