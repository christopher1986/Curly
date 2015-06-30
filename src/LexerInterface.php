<?php

namespace Curly;

/**
 * 
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface LexerInterface
{
    /**
     * Creates tokens from the given input string.
     *
     * @param string $input the string to tokenize.
     */
    public function tokenize($input);
}
