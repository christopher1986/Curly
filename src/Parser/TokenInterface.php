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

use Curly\Common\EquatableInterface;

/**
 * The TokenInterface describes the methods that allows a token to store a value and to identify itself.
 *
 * A token is created during the process of lexical analysis (tokenization). Lexical analysis is
 * the process of converting a sequence of characters into a sequence of tokens.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 */
interface TokenInterface extends EquatableInterface
{
    /**
     * Returns the token type.
     *
     * @return string
     */
    public function getType();
    
    /**
     * Returns the value stored by this token.
     *
     * Although the value stored by a token can be of any type, it's most likely that a token
     * will store a sequence of characters found through a process known as tokenization.
     *
     * @return mixed the value stored by this token.
     */
    public function getValue();
    
    /**
     * Returns if present the line number, or -1 if no line number is available.
     *
     * @return int the line number.
     */
    public function getLineNumber();
    
    /**
     * Returns the literal name for the specified token type.
     *
     * @param mixed $type the token type whose literal name to return.
     * @param bool $strict determines whether strict comparison is required, defaults to false.
     * @return string|null the literal name for the specified token type, or null on failure.
     */
    public static function getLiteral($type, $strict = false);
}
