<?php

namespace Curly;

/**
 * This interface provides capabilities needed for user-defined error handling using the 
 * {@link set_error_handler($error_handler, $error_types)} function and will it throw a
 * {@link Exception} or one of it's subclasses containing the actual error message.
 *
 *
 * This interface is capable of restoring the previous error handler by simply calling
 * the {@link ErrorHandlerInterface::restore()} method.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface ErrorHandlerInterface
{
    /**
     * Register the error handler.
     *
     * @param int $errorTypes (optional) one or more error types to handle.
     * @return mixed the previous error handler.
     * @see http://php.net/manual/en/errorfunc.constants.php error constants
     */
    public static function register($errorTypes = null);
    
    /**
     * Restores the previous error handler.
     */
    public function restore();
    
    /**
     * Throws an {@link Exception} containing the error message for a PHP warning or error.
     *
     * @param int $level the level of the error raised.
     * @param string $message the error message.
     * @param string $file (optional) the filename from which the error was raised.
     * @param int|null $line (optional) the line number from which the error was raised.
     * @param array $context (optional) the active symbol table where the error occurred.
     * @throws Exception the exception containing the error message.
     * @see http://php.net/manual/en/function.set-error-handler.php set_error_handler
     */
    public function handle($level, $message, $file = '', $line = null, array $context = array());
}
