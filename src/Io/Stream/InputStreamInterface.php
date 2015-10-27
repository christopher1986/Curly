<?php 

namespace Curly\Io\Stream;

/**
 * The InputStreamInterface will read a sequence of characters from a storage system or
 * some other source, which may include a {@link OutputStreamInterface} instance.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface InputStreamInterface
{
    /** 
     * Read a sequence of characters from the stream.
     *
     * @param int $length the number of bytes to read.
     * @param int|null $offset (optional) the position to start reading from. 
     * @return string the characters read from the stream.
     */
    public function read($length = 1, $offset = null);
    
    /**
     * Reads all characters from the stream.
     *
     * @return string the entire stream content.
     */
    public function readAll();
    
    /**
     * Reset the pointer to the start of the stream.
     *
     * @return void
     */
    public function reset();
    
    /**
     * Close the input stream. A closed stream cannot be reopened.
     *
     * @return void
     */
    public function close();
}
