<?php
/**
 * Copyright (c) 2015, Chris Harris.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of the copyright holder nor the names of its 
 *     contributors may be used to endorse or promote products derived 
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author     Chris Harris <c.harris@hotmail.com>
 * @copyright  Copyright (c) 2015 Chris Harris
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */

namespace Curly\Parser;

use ReflectionClass;

/**
 * A token stores meaningful character strings that are found when peforming lexical analysis.
 * 
 * A token should consists of a name by which it can be identified and an optional value. The name of
 * a token does not have to be unique amongst other tokens. The name of a token is simply used to hint 
 * what value is stored by the token. The value stored by a token can be of any type, but it's most 
 * likely that a token is used to stored a sequence of characters found with a lexer.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 */
class Token implements TokenInterface
{
    /**
     * Tokens types
     */
    const T_UNKNOWN           = 1;
    const T_TEXT              = 2;
    const T_OPEN_TAG          = 3;
    const T_CLOSE_TAG         = 4;
    const T_OPEN_PRINT_TAG    = 5;
    const T_CLOSE_PRINT_TAG   = 6;
    const T_OPERATOR          = 7;
    const T_INTEGER           = 8;
    const T_FLOAT             = 9;
    const T_STRING            = 10;
    const T_BOOLEAN           = 11;
    const T_NULL              = 12;
    const T_IDENTIFIER        = 13;
    const T_VARIABLE          = 14;
    const T_OPEN_BRACKET      = 15;
    const T_CLOSE_BRACKET     = 16;
    const T_OPEN_PARENTHESIS  = 16;
    const T_CLOSE_PARENTHESIS = 17;
    const T_OPEN_BRACE        = 18;
    const T_CLOSE_BRACE       = 19;
    const T_PERIOD            = 20;
    const T_COMMA             = 21;
    const T_PIPELINE          = 22;
    const T_SEMICOLON         = 23;
    const T_COLON             = 24; 
    const T_ASSIGN            = 25;

    /**
     * The token type.
     *
     * @var mixed
     */
    private $type;

    /**
     * The value stored by this token.
     * 
     * @var mixed
     */
    private $value;

    /**
     * The line number of the value.
     *
     * @var int
     */
    private $lineNumber = -1;
    
    /**
     * Construct a new token.
     *
     * @param mixed $type The token type.
     * @param mixed $value The value for this token.
     * @param int $lineNumber (optional) the line number of the value.
     */
    public function __construct($type, $value, $lineNumber = -1)
    {
        $this->setType($type);
        $this->setValue($value);
        $this->setLineNumber($lineNumber);
    }
    
    /**
     * Set the type of this token. The type is simply used to hint the parser what value is stored by the token.
     *
     * @param mixed $type the token type.
     * @throws InvalidArgumentException if the given argument is not a string.
     */
    private function setType($type)
    {            
        $this->type = $type;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * The value to hold by this token.
     *
     * @param mixed $value the token value.
     */
    private function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Set the line number of the value.
     *
     * @param int $position the line number of the value.
     * @throws InvalidArgumentException if the given position is not a numeric value.
     */
    private function setLineNumber($lineNumber)
    {
        if (!is_numeric($lineNumber)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a numeric argument; received "%s"',
                __METHOD__,
                (is_object($lineNumber) ? get_class($lineNumber) : gettype($lineNumber))
            ));
        }
        
        $this->lineNumber = (int) $lineNumber;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }
    
    /**
     * {@inheritDoc}
     */
    public function equals($obj)
    {
        if ($obj instanceof self) {
            return ($obj->getType() === $this->getType() && $obj->getValue() === $this->getValue());
        }
        
        return false;
    }
    
    /**
     * {@inheritDoc}
     */
    public static function getLiteral($type, $strict = false)
    {
        static $reflClass;
        if ($reflClass === null) {
            $reflClass = new ReflectionClass(get_called_class());
        }
        
        $constants = $reflClass->getConstants();
        foreach ($constants as $name => $value) {
            $matches = ($strict) ? ($type === $value) : ($type == $value);
            if ($matches) {
                return $name;
            }
        }
        
        return null;
    }
}
