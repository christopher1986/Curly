<?php

namespace Curly\Loader;

/**
 * This class provides a skeleton implementation of the {@link LoaderInterface} which minimizes
 * the effort required to implement this interface.
 *
 * @author Chris Harris <c.harris@hotmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AbstractLoader implements LoaderInterface
{
    /**
     * A subsequent loader to handle the input.
     *
     * @var LoaderInterface
     */
    protected $next = null;
    
    /**
     * {@inheritdoc}
     */
    public function setLoader(LoaderInterface $loader)
    {
        if ($this->next !== null) {
            $loader->setLoader($this->next);
        }

        $this->next = $loader;
    }
}
