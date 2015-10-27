<?php 

namespace Curly;

/**
 * A template for the Curly language. A {@link TemplateInterface} object is capable
 * of rendering a template with a specific template context. 
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface TemplateInterface
{
    /**
     * Render this template with the specified context.
     *
     * @param ContextInterface $context the context.
     */
    public function render(ContextInterface $context);
}
