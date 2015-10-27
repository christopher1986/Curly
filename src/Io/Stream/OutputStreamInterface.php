<?php

namespace Curly\Io\Stream;

/**
 * The OutputStreamInterface acepts a sequence of bytes which are written to a storage system 
 * once the {@link StreamInterface::flush()} method is called.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface OutputStreamInterface 
{
    /** 
     * Write the specified string to the stream.
     *
     * @param string $str the string that is written to the stream.
     */
    public function write($str);
    
    /**
     * Flush the stream and write any remaining bytes to their intended destination.
     *
     * @return void
     */
    public function flush();
    
    /**
     * Close and flush the stream. A closed stream cannot be reopened. 
     *
     * return void
     */
    public function close();
}
