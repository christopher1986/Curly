<?php

namespace Curly\Lang\Statement;

use SplStack;

use Curly\Lang\StatementInterface;

abstract class AbstractStatement implements StatementInterface
{    
    /**
     * Returns a parser that converts a collection of tokens into an abstract syntax tree (AST).
     *
     * @return SubparserInterface object to parse a collection of tokens.
     */
    abstract protected function getParser();

    /**
     * {@inheritDoc}
     */
    public function parse(SplStack $tokens)
    {
        $node = null;
        if (($parser = $this->getParser()) !== null) {
            $node = $parser->parse($tokens); 
        }
        
        return $node;
    }

    /**
     * {@inheritDoc}
     */
    public function compareTo($obj)
    {
        if ($obj instanceof self) {
            if (strlen($this->getKeyword()) == strlen($obj->getKeyword())) {
                return 0;
            }
            return (strlen($this->getKeyword()) > strlen($obj->getKeyword())) ? 1 : -1;
        }
        return 0;
    }
}
