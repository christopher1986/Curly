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
use Curly\Lang\Operator\Binary\Arithmetic\AdditionOperator;
use Curly\Lang\Operator\Binary\Arithmetic\DivisionOperator;
use Curly\Lang\Operator\Binary\Arithmetic\MultiplicationOperator;
use Curly\Lang\Operator\Binary\Arithmetic\RemainderOperator;
use Curly\Lang\Operator\Binary\Arithmetic\SubtractionOperator;
use Curly\Lang\Operator\Binary\Membership\InOperator;
use Curly\Lang\Operator\Binary\Membership\NotInOperator;
use Curly\Lang\Operator\Unary\Arithmetic\NegationOperator;
use Curly\Lang\Operator\Unary\Arithmetic\PlusOperator;
use Curly\Lang\Operator\Unary\Logical\NotOperator;
use Curly\Lang\Tag\AssignmentTag;
use Curly\Lang\Tag\DeclarationTag;
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
        $library = $this->getLibrary();
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
    public function getKeywords()
    {
        $library  = $this->getLibrary();
        $keywords = new HashSet();
        
        $tags = $library->getTags();
        foreach ($tags as $tag) {
            $keywords->addAll($tag->getTags());
        }
        
        return $keywords;
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
            new AssignmentTag(),
            new DeclarationTag(),
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
            new DivisionOperator(),
            new MultiplicationOperator(),
            new RemainderOperator(),
            new SubtractionOperator(),
            new InOperator(),
            new NotInOperator(),
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
