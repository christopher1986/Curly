<?php

namespace Curly;

/**
 * Interface to be implemented by any object which gives access to it's EngineInterface instance.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface EngineCapableInterface
{
    /**
     * Returns an EngineInterface instance.
     *
     * @return EngineInterface an EngineInterface instance.
     */
    public function getEngine();
}
