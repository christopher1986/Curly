<?php

namespace Curly\Parser\Exception;

/**
 * Thrown to indicate that a non-existent variable is referenced.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class ReferenceException extends \RuntimeException
{
    /**
     * Construct a new ReferenceException.
     *
     * @param string $message the Exception message to throw.
     * @param int $lineNumber (optional) the line number on which the exception occurred.
     * @param int $code (optional) the exception code.
     * @param Exception $previous (optional) the previous exception used for the exception chaining.
     */
    public function __construct($message, $lineNumber = 0, $code = 0, \Exception $previous = null)
    {
        $errorMsg = $message;
        if (is_numeric($lineNumber) && $lineNumber > 0) {
            $errorMsg = sprintf('%s on line: %d', $message, (int) $lineNumber);
        }
        
        parent::__construct($errorMsg, $code, $previous);
    }
}
