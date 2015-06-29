<?php

namespace Curly;

interface LexerInterface
{
    /**
     * Creates tokens from the given input string.
     *
     * @param string $input the string to tokenize.
     */
    public function tokenize($input);
}
