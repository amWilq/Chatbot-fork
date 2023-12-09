<?php

namespace App\Shared\Traits;

trait HelperTrait
{

    /**
     * Check if arrays are equal.
     */
    public function arraysAreEqual(array $array, array $array2): bool
    {
        return count($array) === count($array2) && array_diff($array, $array2) === array_diff($array2, $array);

    }
}