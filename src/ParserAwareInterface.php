<?php 

namespace Curly;

/**
 * Interface to be implemented by any object that depends on an ParserInterface instance.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface ParserAwareInterface
{
    /**
     * Set the ParserInterface instance.
     *
     * @param ParserInterface $parser a ParserInterface instance.
     */
    public function setParser(ParserInterface $parser);
}
