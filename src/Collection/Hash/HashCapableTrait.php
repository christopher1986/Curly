<?php

namespace Curly\Collection\Hash;

use ReflectionClass;

trait HashCapableTrait
{
    /**
     * Allows the trait to introspect a class.
     *
     * @var ReflectionClass
     */
    private function $refClass;

    /**
     * Returns a unique identifier for this object.
     * 
     * The algorithm used to generate the hash is a balance between practicality and performance.
     * In some rare situations this may lead to two identical hashes for different objects. 
     *
     * @return string a unique identifier.
     */
    public function getHashCode()
    {        
        if ($this->refClass === null) {
            $this->refClass = new ReflectionClass($this);
        }
    
        $hash = '';
    
        $props = $this->refClass->getProperties();
        foreach ($props as $prop) {
            $prop->setaccessible(true);
            $hash .= $this->computeHash($prop->getValue($this));
        }
    
        return md5($hash);
    }
    
    /**
     * Computes the hash for the specified item.
     *
     * @param mixed $item the item whose hash to compute.
     * @return string the computed hash for the specified item, or '0' on failure.
     */
    private function computeHash($item)
    {
        if (is_object($item)) {
            if ($item instanceof HashCapableInterface) {
                return $item->hashCode();
            }
            
            return sprintf('obj_%s', md5(serialize($item)));
        } else if (is_array($item)) {
            return sprintf('arr_%s', md5(serialize($item)));
        } else if (is_resource($item)) {
            return sprintf('res_%s', $item);
        } else if (is_scalar($item)) {
            return sprintf('str_%s', $item);
        }
        
        return '0';
    }
}
