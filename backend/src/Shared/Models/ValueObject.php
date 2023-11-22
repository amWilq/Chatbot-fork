<?php

namespace App\Shared\Models;

abstract class ValueObject
{
    /**
     * Check if the current object is equal to the given object
     */
    abstract public function equals(ValueObject $object): bool;
}
