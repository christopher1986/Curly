<?php 

namespace Curly;

use Curly\Collection\HashSet;
use Curly\Loader\StringLoader;
use Curly\Parser\Exception\TemplateNotFoundException;

use Curly\Lang\Filter as Filter;
use Curly\Lang\Literal as Literal;
use Curly\Lang\Operator\Binary as Binary;
use Curly\Lang\Operator\Unary as Unary;
use Curly\Lang\Statement as Statement;
use Curly\Lang\Tag as Tag;

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
     * A collection of engine options.
     *
     * @var array
     */
    private $options = array(
        'strict_variables' => false
    );

    /**
     * Construct a new Engine.
     *
     * @param array $options (optional) a collection of engine options.
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    
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
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setOption($name, $value)
    {
        $oldValue = $this->getOption($name);
        $this->options[$name] = $value;   
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOption($name, $default = null)
    {
        return ($this->hasOption($name)) ? $this->options[$name] : $default;
    }
    
    /**
     * {@inheritDoc}
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Register default statements with the library.
     */
    private function defaultStatements()
    {
        $statements = array(
            'for'   => new Statement\ForStatement(),
            'if'    => new Statement\IfStatement(),
            'print' => new Statement\PrintStatement(),
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
            'join'          => new Filter\JoinFilter(),
            'number_format' => new Filter\NumberFormatFilter(),
            'lower'         => new Filter\LowerFilter(),
            'upper'         => new Filter\UpperFilter(),
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
            'range' => new Tag\RangeTag(),
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
            new Literal\ArrayLiteral(),
            new Literal\BooleanLiteral(),
            new Literal\DictionaryLiteral(),
            new Literal\FloatLiteral(),
            new Literal\IntegerLiteral(),
            new Literal\NullLiteral(),
            new Literal\StringLiteral(),
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
            new Binary\AdditionOperator(),
            new Binary\AndOperator(),
            new Binary\AssignmentOperator(),
            new Binary\DivisionOperator(),
            new Binary\EqualOperator(),
            new Binary\GreaterEqualOperator(),
            new Binary\GreaterOperator(),
            new Binary\InOperator(),
            new Binary\LessEqualOperator(),
            new Binary\LessOperator(),
            new Binary\MultiplicationOperator(),
            new Binary\NotEqualOperator(),
            new Binary\NotInOperator(),
            new Binary\OrOperator(),
            new Binary\RemainderOperator(),
            new Binary\SubtractionOperator(),
            new Unary\MinusOperator(),
            new Unary\NegationOperator(),
            new Unary\PlusOperator(),
            new Unary\TypeofOperator(),
        );
        
        $library = $this->getLibrary();
        foreach ($operators as $operator) {
            $library->registerOperator($operator->getSymbol(), $operator);
        }
    }
}
