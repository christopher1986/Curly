<?php

namespace Curly\Common\Exception;

use ErrorException;

/**
 * Thrown to indicate that an error in user code has occurred.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 * @link http://php.net/manual/en/errorfunc.constants.php PHP core constants
 */
class UserErrorException extends ErrorException
{}
