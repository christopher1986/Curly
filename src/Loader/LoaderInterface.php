<?php

namespace Curly\Loader;

/**
 * A loader is responsible for loading and returning the content of the specified input.
 * 
 * The underlying process of a loader consists of two steps. In the first step the loader
 * will determine if it's capable of handling the specified input, if not it will try to
 * delegate the input to next loader creating a chain of loaders. A loader that is capable
 * of handling the specified input will load the specified input and return it's content.
 * 
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
interface LoaderInterface
{
    /**
     * Returns if loadable the content of the specified input.
     *
     * @param string $input the input from which to load content.
     * @return string|null the content from the specified input, or null on failure.
     */
    public function load($input);
    
    /**
     * Set the next loader to try if this loader can not load the specified input.
     *
     * @param LoaderInterface $loader the next loader.
     */
    public function setLoader(LoaderInterface $loader);
}
