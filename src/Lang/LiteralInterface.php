<?php

namespace Curly\Lang;

use Curly\SubparserInterface;
use Curly\Parser\TokenInterface;

/** 
 *
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface LiteralInterface extends SubparserInterface
{
    /**
     * Returns the token type which this literal is associated with.
     *
     * @return int the token type this literal is associated with.
     * @see TokenInterface::getType()
     * @see Lexer
     */
    public function getIdentifier();
}
