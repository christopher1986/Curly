<?php

namespace Curly\Parser\Exception;

/**
 * A runtime exception which is thrown to indicate a syntax error was encountered.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
class SyntaxException extends \RuntimeException
{
    public function __construct($message, $lineNumber = -1, $code = 0, \Exception $previous = null)
    {
        $errorMsg = sprintf('%s on line: %d', $message, (int) $lineNumber);
        parent::__construct($errorMsg, $code, $previous);
    }
}
