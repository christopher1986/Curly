<?php 

namespace Curly;

use Curly\Collection\HashSet;
use Curly\Lang\Filter\JoinFilter;
use Curly\Lang\Filter\LowerFilter;
use Curly\Lang\Filter\UpperFilter;
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
use Curly\Lang\Operator\Unary\MinusOperator;
use Curly\Lang\Operator\Unary\NegationOperator;
use Curly\Lang\Operator\Unary\PlusOperator;
use Curly\Lang\Operator\Unary\TypeofOperator;
use Curly\Lang\Statement\ForStatement;
use Curly\Lang\Statement\IfStatement;
use Curly\Lang\Statement\PrintStatement;
use Curly\Lang\Tag\RangeTag;
use Curly\Loader\StringLoader;
use Curly\Parser\Exception\TemplateNotFoundException;

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
     * @var LibraryInterface
     */
    private $library = null;
    
    /**
     * A template loader.
     *
     * @var LoaderInterface
     */
    private $loader = null;
    
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
        $this->defaultStatements();
        $this->defaultFilters();
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
     * Set a {@link LoaderInterface} instance used with which load one or more templates.
     *
     * @param LoaderInterface $loader the loader with which to load templates.
     */
    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }
    
    /**
     * Returns a {@link LoaderInterface} instance used to load one or more templates.
     *
     * @return LoaderInterface a template loader.
     */
    public function getLoader()
    {
        if ($this->loader === null) {
            $this->loader = new StringLoader();
        }
    
        return $this->loader;
    }
    
    /**
     * {@inheritDoc}
     *
     * @throws TemplateNotFoundException a template could not be instantiated with the specified input.
     * @throws InvalidArgumentException if the specified argument is not a string.
     */
    public function loadTemplate($input)
    {
        $loader  = $this->getLoader();
        $content = $loader->load($input);
        
        if ($content === null) {
            throw new TemplateNotFoundException('unable to load template with the specified input');
        }
        
        return new Template($content, $this);
    }

    /**
     * Register default statements with the library.
     */
    private function defaultStatements()
    {
        $statements = array(
            'for'   => new ForStatement(),
            'if'    => new IfStatement(),
            'print' => new PrintStatement(),
        );
        
        $library = $this->getLibrary();
        foreach ($statements as $name => $statement) {
            $library->registerStatement($name, $statement);
        }
    }

    /**
     * Register default filters with the library.
     */
    private function defaultFilters()
    {
        $filters = array(
            'join'  => new JoinFilter(),
            'lower' => new LowerFilter(),
            'upper' => new UpperFilter(),
        );
        
        $library = $this->getLibrary();
        foreach ($filters as $name => $filter) {
            $library->registerFilter($name, $filter);
        }
    }

    /**
     * Register default tags with the library.
     */
    private function defaultTags()
    {
        $tags = array(
            'range' => new RangeTag(),
        );
        
        $library = $this->getLibrary();
        foreach ($tags as $name => $tag) {
            $library->registerTag($name, $tag);
        }
    }
    
    /**
     * Register default literals with the library.
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
     * Register default operators with the library.
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
            new MinusOperator(),
            new NegationOperator(),
            new PlusOperator(),
            new TypeofOperator(),
        );
        
        $library = $this->getLibrary();
        foreach ($operators as $operator) {
            $library->registerOperator($operator->getSymbol(), $operator);
        }
    }
}
