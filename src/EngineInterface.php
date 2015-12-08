<?php 

namespace Curly;

/**
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface EngineInterface
{
    /**
     * Returns a set of reserved operator symbols.
     *
     * @return SetInterface a set of operator symbols.
     */
    public function getOperatorSymbols();
    
    /**
     * Returns a lexer to perform lexical analysis on an input string. 
     *
     * @return LexerInterface a lexer.
     */
    public function getLexer();
    
    /**
     * Returns a parser to perform syntactical analysis on a sequence of tokens.
     *
     * @return ParserInterface a parser.
     */
    public function getParser();
    
    /**
     * Returns a library which contains registered filter, operators and tags.
     *
     * @return LibraryInterface a library.
     */
    public function getLibrary();
    
    /**
     * Returns a {@link TemplateInterface} instance for the specified input.
     *
     * @param string $input the input for which to load a template.
     * @return TemplateInterface the template for the specified input.
     */
    public function loadTemplate($input);
    
    /**
     * Set a collection of key-value pairs of options.
     *
     * @param array $options an associative array of options.
     */
    public function setOptions(array $options);
    
    /**
     * Returns a collection containing key-value pairs of options.
     *
     * @return array an associative array of options.
     */
    public function getOptions();
    
    /**
     * Set an option for the specified name and value.
     *
     * @param string $name the name of the option.
     * @param mixed $value the options value.
     * @return mixed|null the previous option associated with the specified name, or null if no option exits for the specified name.
     */
    public function setOption($name, $value);
    
    /**
     * Returns an option for the specified name.
     *
     * @param string $name the name for whose associated option will be returned.
     * @param mixed $default the default value to return if no option exists for the specified name.
     * @return mixed the option associated with the specified name, or the default value if no option exists for the specified name.
     */
    public function getOption($name, $default = null);
    
    /**
     * Returns true if an option exists for the specified name.
     *
     * @param string $name the name whose presence will be tested.
     * @return bool true if an option exists for the specified name, otherwise false.
     */
    public function hasOption($name);
}
