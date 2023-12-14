<?php

namespace App\Shared\Models;

interface EntityToArrayInterface
{
    /**
     * Returns the object as an array.
     */
    public function toArray(): array;
}
