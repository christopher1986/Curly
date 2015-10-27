<?php

namespace Curly\Io\Stream;

use Curly\Io\Exception\IOException;

/**
 * The PhpOutputStream is thin wrapper around the PHP output stream.
 * 
 * This write-only stream allows you to write bytes to the output
 * buffer mechanism in the same way as print and echo.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class PhpOutputStream implements OutputStreamInterface
{
    /**
     * A PHP output stream.
     *
     * @var resource
     */
    private $stream = null;
    
    /**
     * Construct a new OutputStream.
     */
    public function __construct()
    {
        $this->stream = fopen('php://output', 'w');
    }

    /** 
     * {@inheritDoc}
     *
     * @throws IOException if the stream has been closed.
     */
    public function write($str)
    {    
        $this->ensureOpen();
        fputs($this->stream, $str);
    }
    
    /** 
     * {@inheritDoc}
     *
     * This stream does not use a buffer which can be flushed, so calling the {@link StreamInterface::flush()}
     * method is considered a no-op. Buffers allow data to be temporary stored in memory, which is normally
     * only necessary when continuous writing of data would be too expensive.
     */
    public function flush()
    {}
    
    /** 
     * {@inheritDoc}
     */
    public function close()
    {
        if (fclose($this->stream)) {
            $this->stream = null;
        }
    }
    
    /**
     * Ensures that the underlying stream is still open.
     *
     * @throws IOException if the stream has been closed.
     */
    protected function ensureOpen()
    {
        if ($this->stream === null) {
            throw new IOException('Stream closed');
        }
    }
}
