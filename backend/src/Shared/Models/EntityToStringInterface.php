<?php

namespace App\Shared\Models;

interface EntityToStringInterface
{
    /**
     * Returns the object as a string.
     */
    public function toString(): string;
}
