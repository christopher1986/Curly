<?php

namespace Curly\Lang;

use Curly\SubparserInterface;

/**
 * An Operator perfoms an operation on one or more expressions, also known as it's operands.
 * Most common operators are addition, subtraction, division and multiplication. All operators
 * have a symbol by which they are identified and a precedence that determines the sequence in
 * which a collection of operator are evaluated.
 *
 * @author Chris Harris 
 * @version 1.0.0
 * @since 1.0.0
 */
interface OperatorInterface
{
    /**
     * Returns the operator phrase or symbol.
     *
     * @return string a phrase or symbol.
     */
    public function getSymbol();
    
    /**
     * Returns the operator precedence.
     *
     * @return int the precedence.
     */
    public function getPrecedence();
}
