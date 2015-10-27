<?php

namespace Curly\Io\Stream;

use Curly\Io\Exception\IOException;

/**
 * The PipedOutputStream is a write stream which sends the bytes of characters it receives 
 * to a {@link PipedInputStream} instance.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class PipedOutputStream implements OutputStreamInterface
{
    /**
     * The connected stream.
     *
     * @var PipedInputStream
     */
    private $stream = null;
    
    /**
     * Construct a new PipedOutputStream.
     *
     * @param PipedInputStream (optional) $stream the stream to connect with.
     */
    public function __construct(PipedInputStream $stream = null)
    {
        if ($stream !== null) {
            $this->connect($stream);
        }
    }

    /** 
     * {@inheritDoc}
     *
     * @throws IOException if the stream is not connected.
     */
    public function write($str)
    {    
        if ($this->stream === null) {
            throw new IOException("no pipe connected which can receive bytes");
        }
        
        $this->stream->receive($str);
    }
    
    /**
     * Connect this stream with the specified {@link PipedInputStream} instance.
     *
     * @param PipedInputStream $stream the stream to connect with.
     * @throws IOException if this stream is already connected.
     */
    public function connect(PipedInputStream $stream)
    {
        if ($this->stream !== null) {
            throw new IOException('already connected to another stream.');
        }
    
        $this->stream = $stream;
    }
    
    /** 
     * {@inheritDoc}
     *
     * This stream writes all bytes to a input stream, so calling the {@link StreamInterface::flush()}
     * method is considered a no-op.
     */
    public function flush()
    {}
    
    /** 
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->stream !== null) {
            $this->stream->close();
            $this->stream = null;
        }
    }
}
