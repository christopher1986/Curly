<?php 

namespace Curly;

use Curly\Io\Stream\PipedInputStream;
use Curly\Io\Stream\PipedOutputStream;

use Webwijs\Error\ErrorHandler;

/**
 * A concrete implementation of the {@link TemplateInterface} and will render
 * the underlying template content with the specified template context.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class Template implements TemplateInterface
{
    /**
     * The content to render. 
     *
     * @var string
     */
    private $content;

    /**
     * The engine used to render this template.
     *
     * @var EngineInterface
     */
    private $engine;

    /**
     * Construct a new Template.
     *
     * @param string $content the content to render.
     * @param EngineInterface the template engine.
     */
    public function __construct($content, EngineInterface $engine)
    {
        $this->setContent($content);
        $this->setEngine($engine);
    }
    
    /**
     * {@inheritDoc}
     */
    public function render(ContextInterface $context)
    {
        $in  = new PipedInputStream();
        $out = new PipedOutputStream($in);
        
        $lexer  = $this->engine->getLexer();
        $parser = $this->engine->getParser();
        
        $handler = ErrorHandler::register();
        $parser->parse($lexer->tokenize($this->content))->render($context, $out);        
        $handler->restore();
        
        $output = $in->readAll();
        
        $in->close();
        $out->close();
        
        return $output;
    }
    
    /**
     * Set the content to render.
     *
     * @param string $content the content to render.
     * @throws InvalidArgumentException if the specified argument is not a string.
     */
    private function setContent($content)
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string argument; received "%s"',
                __METHOD__,
                (is_object($content) ? get_class($content) : gettype($content))
            ));
        }
        
        $this->content = $content;
    }
    
    /**
     * Returns the content that needs to be rendered.
     *
     * @return string the content to render.
     */
    private function getContent()
    {
        return $this->content;
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
     * Returns the template engine.
     *
     * @return EngineInterface the template engine.
     */
    private function getEngine()
    {
        return $this->engine;
    }
}
