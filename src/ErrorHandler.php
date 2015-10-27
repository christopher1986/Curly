<?php

namespace Curly;

use ErrorException;
    
use Curly\Common\Exception\CompileErrorException;
use Curly\Common\Exception\CompileWarningException;
use Curly\Common\Exception\CoreErrorException;
use Curly\Common\Exception\CoreWarningException;
use Curly\Common\Exception\DeprecatedException;
use Curly\Common\Exception\NoticeException;
use Curly\Common\Exception\ParseException;
use Curly\Common\Exception\RecoverableErrorException;
use Curly\Common\Exception\StrictException;
use Curly\Common\Exception\UserDeprecatedException;
use Curly\Common\Exception\UserErrorException;
use Curly\Common\Exception\UserNoticeException;
use Curly\Common\Exception\UserWarningException;
use Curly\Common\Exception\WarningException;

/**
 * A basic error handler that implements the {@link ErrorHandlerInterface}.
 *
 * This error handler will throw a {@link ErrorException} if an error or warning
 * occurs during the lexical or syntactical analysis.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * The previous error handler.
     *
     * @var mixed
     */
    private $errorHandler = null;

    /**
     * Construct a new ErrorHandler.
     *
     * @param int $errorTypes one or more error types to handle.
     */
    protected function __construct($errorTypes)
    {
        $this->errorHandler = set_error_handler(array($this, 'handle'), $errorTypes);
    }

    /**
     * {@inheritdoc}
     */
    public static function register($errorTypes = null)
    {
        if ($errorTypes === null) {
            $errorTypes = E_ALL | E_STRICT;
        }
    
        return new static($errorTypes);
    }
    
    /**
     * {@inheritdoc}
     */
    public function restore()
    {
        if (is_callable($this->errorHandler)) {
            set_error_handler($this->errorHandler);
        } else {
            restore_error_handler();
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function handle($level, $message, $file = '', $line = null, array $context = array())
    {
        if (0 === error_reporting()) {
            return false;
        }
    
        switch ($level) {
            case E_WARNING:
                throw new WarningException($message, 0, $level, $file, $line);
                break;
            case E_PARSE:
                throw new ParseException($message, 0, $level, $file, $line);
                break;
            case E_NOTICE:
                throw new NoticeException($message, 0, $level, $file, $line);
                break;
            case E_CORE_ERROR:
                throw new CoreErrorException($message, 0, $level, $file, $line);
                break;
            case E_CORE_WARNING:
                throw new CoreWarningException($message, 0, $level, $file, $line);
                break;
            case E_COMPILE_ERROR:
                throw new CompileErrorException($message, 0, $level, $file, $line);
                break;
            case E_COMPILE_WARNING:
                throw new CoreWarningException($message, 0, $level, $file, $line);
                break;
            case E_USER_ERROR:
                throw new UserErrorException($message, 0, $level, $file, $line);
                break;
            case E_USER_WARNING:
                throw new UserWarningException($message, 0, $level, $file, $line);
                break;
            case E_USER_NOTICE:
                throw new UserNoticeException($message, 0, $level, $file, $line);
                break;
            case E_STRICT:
                throw new StrictException($message, 0, $level, $file, $line);
                break;
            case E_RECOVERABLE_ERROR:
                throw new RecoverableErrorException($message, 0, $level, $file, $line);
                break;
            case E_DEPRECATED:
                throw new DeprecatedException($message, 0, $level, $file, $line);
                break;
            case E_USER_DEPRECATED:
                throw new UserDeprecatedException($message, 0, $level, $file, $line);
                break;
            case E_ERROR:
            default:
                throw new ErrorException($message, 0, $level, $file, $line);
                break;
        }
    }
}
