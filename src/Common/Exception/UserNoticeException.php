<?php

namespace Curly\Common\Exception;

use ErrorException;

/**
 * Thrown to indicate that the user code has one or more notices.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 * @link http://php.net/manual/en/errorfunc.constants.php PHP core constants
 */
class UserNoticeException extends ErrorException
{}
