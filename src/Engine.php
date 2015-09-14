<?php 

namespace Curly;

use Curly\Collection\HashSet;
use Curly\Lang\Literal\ArrayLiteral;
use Curly\Lang\Literal\BooleanLiteral;
use Curly\Lang\Literal\DictionaryLiteral;
use Curly\Lang\Literal\FloatLiteral;
use Curly\Lang\Literal\IntegerLiteral;
use Curly\Lang\Literal\NullLiteral;
use Curly\Lang\Literal\StringLiteral;
use Curly\Lang\Operator\Binary\AdditionOperator;
use Curly\Lang\Operator\Binary\AndOperator;
use Curly\Lang\Operator\Binary\AssignmentOperator;
use Curly\Lang\Operator\Binary\DivisionOperator;
use Curly\Lang\Operator\Binary\EqualOperator;
use Curly\Lang\Operator\Binary\GreaterEqualOperator;
use Curly\Lang\Operator\Binary\GreaterOperator;
use Curly\Lang\Operator\Binary\InOperator;
use Curly\Lang\Operator\Binary\LessEqualOperator;
use Curly\Lang\Operator\Binary\LessOperator;
use Curly\Lang\Operator\Binary\MultiplicationOperator;
use Curly\Lang\Operator\Binary\NotEqualOperator;
use Curly\Lang\Operator\Binary\NotInOperator;
use Curly\Lang\Operator\Binary\OrOperator;
use Curly\Lang\Operator\Binary\RemainderOperator;
use Curly\Lang\Operator\Binary\SubtractionOperator;
use Curly\Lang\Operator\Unary\NegationOperator;
use Curly\Lang\Operator\Unary\PlusOperator;
use Curly\Lang\Operator\Unary\NotOperator;
use Curly\Lang\Tag\ForTag;
use Curly\Lang\Tag\IfTag;
use Curly\Lang\Tag\PrintTag;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
final class Engine implements EngineInterface, LibraryAwareInterface
{    
    /**
     * A template library.
     *
     */
    private $library = null;
    
    /**
     * A lexer to tokenize an input string.
     *
     * @var LexerInterface
     */
    private $lexer = null;

    /**
     * A parser to create an abstract syntax tree.
     *
     * @var ParserInterface
     */
    private $parser = null;

    /**
     * Construct a new Engine.
     */
    public function __construct()
    {
        $this->defaultTags();
        $this->defaultLiterals();
        $this->defaultOperators();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOperatorSymbols()
    {
        $library   = $this->getLibrary();
        $operators = $library->getUnaryOperators();
        $operators->addAll($library->getBinaryOperators());
        
        $symbols = new HashSet();
        foreach ($operators as $operator) {
            $symbols->add($operator->getSymbol());
        }
        
        return $symbols;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLexer()
    {
        if ($this->lexer === null) {
            $this->lexer = new Lexer($this);
        }
        
        return $this->lexer;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getParser()
    {
        if ($this->parser === null) {
            $this->parser = new Parser($this);
        }
        
        return $this->parser;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setLibrary(LibraryInterface $library)
    {
        $this->library = $library;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLibrary()
    {
        if ($this->library === null) {
            $this->library = new Library();
        }
    
        return $this->library;
    }
    

    /**
     * Register default tags with library.
     */
    private function defaultTags()
    {
        $tags = array(
            new ForTag(),
            new IfTag(),
            new PrintTag(),
        );
        
        $library = $this->getLibrary();
        foreach ($tags as $tag) {
            $library->registerTag($tag->getTag(), $tag);
        }
    }
    
    /**
     * Register default literals with library.
     */
    private function defaultLiterals()
    {
        $literals = array(
            new ArrayLiteral(),
            new BooleanLiteral(),
            new DictionaryLiteral(),
            new FloatLiteral(),
            new IntegerLiteral(),
            new NullLiteral(),
            new StringLiteral(),
        );
        
        $library = $this->getLibrary();
        foreach ($literals as $literal) {
            $library->registerLiteral($literal->getIdentifier(), $literal);
        }
    }
    
    /**
     * Register default operators with library.
     */
    private function defaultOperators()
    {
        $operators = array(
            new AdditionOperator(),
            new AndOperator(),
            new AssignmentOperator(),
            new DivisionOperator(),
            new EqualOperator(),
            new GreaterEqualOperator(),
            new GreaterOperator(),
            new InOperator(),
            new LessEqualOperator(),
            new LessOperator(),
            new MultiplicationOperator(),
            new NotEqualOperator(),
            new NotInOperator(),
            new OrOperator(),
            new RemainderOperator(),
            new SubtractionOperator(),
            new NegationOperator(),
            new PlusOperator(),
            new NotOperator()
        );
        
        $library = $this->getLibrary();
        foreach ($operators as $operator) {
            $library->registerOperator($operator->getSymbol(), $operator);
        }
    }
}
