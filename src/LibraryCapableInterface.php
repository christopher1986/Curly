<?php

namespace Curly;

/**
 * Interface to be implemented by any object which gives access to it's LibraryInterface instance.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface LibraryCapableInterface
{
    /**
     * Returns a LibraryInterface instance.
     *
     * @return LibraryInterface a LibraryInterface instance.
     */
    public function getLibrary();
}
