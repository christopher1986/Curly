<?php

namespace Curly\Io\Stream;

use Curly\Io\Exception\IOException;

/**
 * The PipedInputStream is a read stream which connects with a {@link PipedOutputStream} instance. 
 * This allows the output stream to write bytes of characters it receives to this stream.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class PipedInputStream implements InputStreamInterface
{
    /**
     * The underlying temp stream.
     *
     * @var resource
     */
    private $stream = null;
    
    /**
     * Construct a new PipedInputStream.
     *
     * @param array $options (optional) options used by the stream.
     */
    public function __construct(array $options = array())
    {
        $defaults = array(
            'maxmemory' => 2097152,
        );
        $options = array_merge($defaults, $options);
    
        $protocol = sprintf('php://temp/maxmemory:%d', (int) $options['maxmemory']);
        $this->stream = fopen($protocol, 'w');
    }

    /** 
     * {@inheritDoc}
     *
     * @throws IOException if the stream is unable to read characters.
     */
    public function read($length = 1, $offset = null)
    {    
        $this->ensureOpen();
        
        if ($offset !== null) {
            $success = fseek($this->stream, $offset);
            if (!$success) {
                throw new IOException('unable to move stream to the specified offset.');
            }
        }
        
        $content = fread($this->stream, $length);
        if ($content === false) {
            throw new IOException('unable to read characters from the stream.');
        }
        
        return $content;
    }
    
    /**
     * {@inheritDoc}
     *
     * @throws IOException if the stream is unable to read characters.
     */
    public function readAll()
    {
        $this->ensureOpen();
        $this->reset();
        
        $output = stream_get_contents($this->stream);
        
        // set file position to end for PHP 5.1.9 or lower
        if (version_compare(PHP_VERSION, '5.2.0', '<')) {
            fseek($this->stream, 0, SEEK_END);
        }
        
        return $output;
    }
    
    /**
     * Connect this stream with the specified {@link PipedOutputStream} instance.
     *
     * @param PipedOutputStream $stream the stream to connect with.
     * @throws IOException if the specified stream is already connected.
     */
    public function connect(PipedOutputStream $stream)
    {
        $stream->connect($this);
    }
    
    /** 
     * {@inheritDoc}
     *
     * @throws IOException if the stream is closed.
     */
    public function reset()
    {
        $this->ensureOpen();
        fseek($this->stream, 0, SEEK_SET);
    }
    
    /**
     * Receive bytes of characters from the connected stream.
     *
     * @param string $str the characters received from the connected stream.
     * @throws IOException if the stream is closed.
     */
    public function receive($str)
    {
        $this->ensureOpen();
        fputs($this->stream, (string) $str);
    }
    
    /** 
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->stream !== null && fclose($this->stream)) {
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
