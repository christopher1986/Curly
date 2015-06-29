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

namespace Curly\Io;

/**
 * A StringReader is capable of reading characters from a string.
 *
 * @author Chris Harris
 * @version 1.0.0
 */
class StringReader extends AbstractReader
{
    /**
     * The input string on which the Reader operates.
     *
     * @var string
     */
    protected $subject;

    /**
     * The number of characters.
     *
     * @var int
     */
    protected $length = 0;

    /**
     * The position of the Reader.
     *
     * @var int
     */
    protected $cursor = 0;

    /**
     * The current line number.
     *
     * @var int
     */
    protected $lineNumber = 1;

    /**
     * The reader positions marked.
     *
     * @var int|string
     */
    protected $markedCursor = self::UNMARKED;

    /**
     * The line number that is marked.
     *
     * @var int
     */
    protected $markedLineNumber = self::UNMARKED;

    /**
     * Construct a new StringReader.
     *
     * @param string $string the string to read.
     */
    public function __construct($string)
    {
        $this->setString($string);
    }
    
    /**
     * {@inheritDoc}
     */
    public function read($offset, $length)
    {        
        if ($offset < 0 || $length <= 0) {
            throw new \LogicException('The given offset or length are not valid.');
        }

        $length = min(($this->length - $this->cursor), $length);
        $offset = min(($this->length - $length), ($this->cursor + $offset));

        $str = substr($this->subject, $offset, $length);
        if ($str === false) {
            $str = null;
        }

        $this->cursor     += $length;
        $this->lineNumber += substr_count($str, "\n");
           
        return $str;
    }
    
    /**
     * {@inheritDoc}
     */
    public function readChar($amount = 1)
    {
        $chars = null;
        if ($this->hasNextChar()) {
            $chars = $this->read(0, $amount);
        }
        
        return $chars;
    }
    
    /**
     * Returns the character at the given position, or null if the given position is not valid or larger than the number of characters 
     * contained by the Reader.
     *
     * @param int $position the position of the character that will be returned.
     * @return string|null the character that is stored at the given position, or null if the position is not valid.
     */
    public function readCharAt($position)
    {
        $char = null;
        if (isset($this->subject[$position])) {
            $char = $this->subject[$position];
        }

        return $char;
    }
    
    /**
     * Read characters from the Reader until one or more whitespace characters are encountered.
     * 
     * @return string|null a string containing the content of the word, or null if the Reader has reached the end of the string.
     */    
    public function readWord()
    {        
        // move to the first non-whitespace character.
        if (ctype_space($this->getCurrentChar()) && preg_match('/(\s*)/', $this->subject, $matches, null, $this->cursor)) {
            $this->cursor += strlen($matches[1]);
        }
        
        $word = null;
        if ($this->hasNextChar()) {        
            if (preg_match('#(.[^\s]*)#', $this->subject, $matches, null, $this->cursor)) {
                $word = $matches[1];
                $this->cursor += strlen($word);
            }
        }
        
        // increment line number.
        if ($this->hasNextChar() && substr($this->subject, $this->cursor, 1) === "\n") {
            $this->lineNumber++;
        }
        
        return $word;
    }
    
    /**
     * Read characters from the Reader until a line termination character is encountered.
     * 
     * @return string|null a string containing the content of the a line, or null if the Reader has reached the end of the string.
     */
    public function readLine()
    {
        $line = null;
        if ($this->hasNextChar()) {      
            $line = (preg_match('#(.[^\n]*)#s', $this->subject, $matches, null, $this->cursor) === 1) ? $matches[1] : substr($this->subject, $this->cursor);
            // update next char.
            $this->cursor += (strlen($line) > 0) ? strlen($line) : 1;
        }
        
        $this->lineNumber++;
        
        return $line;
    }
    
    /**
     * Reads the remainder of characters from the Reader.
     *
     * @return string|null a string containing the remainder of characters from the Reader, or null if the Reader has reached the end of the string.
     */
    public function readToEnd()
    {
        $str = substr($this->subject, $this->cursor);
        if ($str === false) {
            $str = null;
        }
        
        $this->cursor     += strlen($str);
        $this->lineNumber += substr_count($str, "\n");
        
        return $str;
    }
    
    /**
     * {@inheritDoc}
     */
    public function mark()
    {
        $this->markedCursor     = $this->cursor;  
        $this->markedLineNumber = $this->lineNumber;
    }
    
    /**
     * {@inheritDoc}
     */
    public function reset()
    {
        if ($this->markedCursor < 0) {
            throw new \LogicException(sprintf(
                'No mark has been set, call %s::mark() first.',
                __Class__
            ));
        }
        
        $this->cursor       = $this->markedCursor;
        $this->markedCursor = self::UNMARKED;
        
        $this->lineNumber       = $this->markedLineNumber;
        $this->markedLineNumber = self::UNMARKED;
    }
    
    /**
     * {@inheritDoc}
     */
    public function skip($amount = 1)
    {
        if (!is_numeric($amount) || $amount < 0) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a positive numeric argument; received "%s"',
                __METHOD__,
                (is_object($amount) ? get_class($amount) : gettype($amount))
            ));
        }
        
        $this->read(0, (int) $amount);
    }
    
    /**
     * {@inheritDoc}
     */
    public function peek($amount = 1)
    {
        $str = '';
    
        $this->mark();
        $str = $this->read(0, (int) $amount);
        $this->reset();
        
        return $str;
    }
    
    /**
     * Returns true if the underlying string matches the given regular expression.
     *
     * The {@link preg_match($pattern, $matches, $flags, $offset)} function normally starts the 
     * search at the beginning of the subject string, but since this reader is a forward reader 
     * it will start the search from it's current position. Also this operation will not actually
     * move the reader forward.
     *
     * @param string $pattern the regular expression to match.
     * @param array $matches (optional) if provided, then it's filled with the results of the match.
     * @param int $flags (optional) flags that change what will be returned by the match.
     * @param int $offset (optional) alternate place from which to start the search (in bytes).
     * @return int|bool 1 if the regular expression matches, 0 if it does not, or false if an error occurred.
     * @see http://php.net/manual/en/function.preg-match.php
     */
    public function matches($pattern, &$matches = null, $flags = 0, $offset = 0)
    {
        return preg_match($pattern, $this->subject, $matches, $flags, $offset + $this->cursor);
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return $this->cursor;
    }
    
    /**
     * Returns the current line number.
     *
     * @return int the current line number.
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }
    
    /**
     * Set the string from which this Reader will read.
     *
     * @param string $string the string to read.
     */
    protected function setString($string)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string as argument; received "%s"',
                __METHOD__,
                (is_object($string) ? get_class($string) : gettype($string))
            ));
        }
        
        $this->subject = str_replace(array("\r\n", "\r"), "\n", $string);
        $this->length  = strlen($this->subject);
    }

    /**
     * Determine whether the Reader has reached the end of the sequence of characters.
     *
     * @return bool true if there are still characters left, false otherwise.
     */
    public function hasNextChar()
    {
        return ($this->cursor < $this->length);
    }
    
    /**
     * Returns the current character within the reader.
     *
     * @return string|null the current character.
     */
    protected function getCurrentChar()
    {
        $char = null;
        if (isset($this->content[$this->cursor])) {
            $char = $this->content[$this->cursor];
        }
        
        return $char;
    }
}
