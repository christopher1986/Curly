<?php

namespace Curly;

/**
 * Interface to be implemented by any object that depends on a LibraryInterface instance.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface LibraryAwareInterface
{
    /**
     * Set the LibraryInterface instance.
     *
     * @param LibraryInterface $library a LibraryInterface instance.
     */
    public function setLibrary(LibraryInterface $library);
}
