<?php

namespace Curly;

/**
 * Interface to be implemented by any object that depends on an EngineInterface instance.
 *
 * @author Chris Harris
 * @version 1.0.0
 * @since 1.0.0
 */
interface EngineAwareInterface
{
    /**
     * Set the EngineInterface instance.
     *
     * @param EngineInterface $engine an EngineInterface instance.
     */
    public function setEngine(EngineInterface $engine);
}
