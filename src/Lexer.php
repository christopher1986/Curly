<?php

namespace Curly;

use SplQueue;

use Curly\Collection\Comparator\ObjectComparator;
use Curly\Io\StringReader;
use Curly\Parser\Exception\SyntaxException;
use Curly\Parser\Lexer\Token;
use Curly\Util\Arrays;

class Lexer implements LexerInterface
{
    /**
     * A finite number of states.
     */
    const STATE_TEXT = 0x01;
    const STATE_LANG = 0x02;

    /**
     * Possible tokens types.
     */
    const T_TEXT        = 0x01;
    const T_TAG         = 0x02;
    const T_STATEMENT   = 0x03;
    const T_OPERATOR    = 0x04;
    const T_IDENTIFIER  = 0x05;
    const T_NUMBER      = 0x06;
    const T_STRING      = 0x07;
    const T_PUNCTUATION = 0x08;

    /**
     * The template engine.
     *
     * @var EngineInterface
     */
    private $engine;

    /**
     * A comparison function to sort objects.
     *
     * @var Comparator
     */
    private $comparator;

    /**
     * A collection of tags to match.
     *
     * @var array
     */
    private $tags = array(
        'open'  => '{%',
        'close' => '%}',
    );
    
    /**
     * A collection of tokens found by the lexer.
     *
     * @var SplQueue 
     */
    private $tokens;
    
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
        'number'      => '/([0-9]+(?:\.[0-9]+)?)/A',
        'string'      => '/([\'"])(.*?)(?<!\\\)\1/As',
        'identifier'  => '/([a-z_\x7f-\xff]{1}[a-z0-9_\x7f-\xff]*)/Ai',
        'punctuation' => '/([\[\]\(\)\{\}.,\|;\:\=])/A',
    );

    /**
     * Construct a new Lexer.
     *
     * @param EngineInterface $engine the template engine.
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine     = $engine;
        $this->tokens     = new SplQueue();
        $this->comparator = new ObjectComparator();
    }

    /**
     * {@inheritDoc}
     *
     * @param string $input the data that will be tokenized.
     * @throws InvalidArgumentException if the given argument is not a string.
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
        
        echo '<pre>';
        foreach ($this->tokens as $token) {
            var_dump($token);
        }
    }
    
    private function tokenizeText()
    {    
        $lineNumber = $this->reader->getLineNumber();
        if (($tagPos = array_shift($this->tagPositions)) !== null) {
            if (($amount = $tagPos[1] - $this->reader->getPosition()) > 0) {
                $this->enqueueToken(self::T_TEXT, $this->reader->readChar($amount), $lineNumber);
            }

            $lineNumber = $this->reader->getLineNumber();
            if (($tag = $this->reader->readChar(strlen($tagPos[0]))) === $tagPos[0]) {
                $this->enqueueToken(self::T_TAG, $tag, $lineNumber);
                // update lexer's state.
                $this->state = self::STATE_LANG;
            }            
        } else if ($this->reader->hasNextChar()) {
            $this->enqueueToken(self::T_TEXT, $this->reader->readToEnd(), $lineNumber);
        }
    }
    
    /**
     * 
     *
     * @link http://openbookproject.net/thinkcs/python/english3e/variables_expressions_statements.html
     */
    private function tokenizeLang()
    {    
        // skip preceding whitespace.
        if ($this->reader->matches('/\s+/A', $matches)) {
            $this->reader->skip(strlen($matches[0]));
        }
    
        $matches = array();
        if ($this->reader->matches($this->getTagRegex(), $matches)) {    
            $this->enqueueToken(self::T_TAG, $matches[1], $this->reader->getLineNumber());
            
            // update lexer's state.
            if ($matches[1] == $this->tags['close']) {
                $this->state = self::STATE_TEXT;
            }
        } else if ($this->reader->matches($this->getStatementRegex(), $matches)) {
            $this->enqueueToken(self::T_STATEMENT, $matches[1], $this->reader->getLineNumber());
        } else if ($this->reader->matches($this->getOperatorRegex(), $matches)) {
            $this->enqueueToken(self::T_OPERATOR, $matches[1], $this->reader->getLineNumber());
        } else if ($this->reader->matches($this->regexes['identifier'], $matches)) {
            $this->enqueueToken(self::T_IDENTIFIER, $matches[1], $this->reader->getLineNumber());
        } else if ($this->reader->matches($this->regexes['number'], $matches)) {
            $number = (float) $matches[1];
            if (ctype_digit($matches[1]) && $matches[1] <= PHP_INT_MAX) {
                $number = (int) $number;
            }
        
            $this->enqueueToken(self::T_NUMBER, $number, $this->reader->getLineNumber());
        } else if ($this->reader->matches($this->regexes['string'], $matches)) {
            // unescape quotes.
            $string = str_replace("\\{$matches[1]}", $matches[1], $matches[2]);
            
            $this->enqueueToken(self::T_STRING, $string, $this->reader->getLineNumber());
        } else if ($this->reader->matches($this->regexes['punctuation'], $matches)) {
            $this->enqueueToken(self::T_PUNCTUATION, $matches[1], $this->reader->getLineNumber());
        }

        if ($this->reader->hasNextChar()) {
            if (!isset($matches[0])) {
                $lineNumber = $this->reader->getLineNumber();
                throw new SyntaxException("Unknown character '{$this->reader->readWord()}' was found", $lineNumber);
            }
            
            // move reader forward.
            $this->reader->skip(strlen($matches[0]));
        }
    }
    
    /**
     * Returns a regular expression to match statements from the template engine.
     *
     * @return string regular expression to match statements.
     */
    private function getStatementRegex()
    {     
        if (!isset($this->regexes['statement'])) {
            $statements = $this->engine->getStatements();
            Arrays::sort($statements, $this->comparator, true);
        
            $patterns = array();
            foreach ($statements as $statement) {
                $keyword = preg_quote($statement->getKeyword(), '/');
                if ($statement->isConditional()) {
                    $patterns[] = sprintf('%s(?=[\s\(])', $keyword);
                } else {
                    $patterns[] = sprintf('%s(?=[\s])', $keyword);
                }
            }
            
            $this->regexes['statement'] = sprintf('/(%s)/A', implode('|', $patterns));
        }

        return $this->regexes['statement'];
    }
    
    /**
     * Returns a regular expression to match operators from the template engine.
     *
     * @return string regular expression to match operators.
     */
    private function getOperatorRegex()
    {
        if (!isset($this->regexes['operator'])) {
            $operators = $this->engine->getOperators();
            Arrays::sort($operators, $this->comparator, true);
          
            $patterns = array();
            foreach ($operators as $operator) {
                $patterns[] = preg_quote($operator->getOperator(), '/');
            }
            
            $this->regexes['operator'] = sprintf('/(%s)/A', implode('|', $patterns));
        }

        return $this->regexes['operator'];
    }
    
    /**
     * Returns a regular expression to match tags from the template engine.
     *
     * @return string regular expression to match tags.
     */
    private function getTagRegex()
    {
        if (!isset($this->regexes['tag'])) {
            $patterns = array();
            foreach ($this->tags as $tag) {
                $patterns[] = preg_quote($tag, '/');
            }
                        
            $this->regexes['tag'] = sprintf('/(%s)/A', implode('|', $patterns));
        }

        return $this->regexes['tag'];
    }
    
    
    /**
     * Set the input data that the lexer will tokenize.
     *
     * @param string $input the data that will be tokenized.
     * @throws InvalidArgumentException if the given argument is not a string.
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
        $regex = sprintf('/(%s)/i', preg_quote($this->tags['open'], '/'));
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
        $this->tokens = new SplQueue();
    }
    
    /**
     * Push a new token onto a collection of tokens.
     */
    private function enqueueToken($type, $value = '', $lineNumber = -1)
    {
        $this->tokens->enqueue(new Token($type, $value, $lineNumber));
    }
}
