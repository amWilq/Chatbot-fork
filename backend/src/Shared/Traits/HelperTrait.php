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

    public static function convertNameToClassName(string $name, string $classSuffix): string
    {
        // replace underscores or dashes with spaces
        $name = str_replace(['_', '-'], ' ', $name);

        // convert to PascalCase
        $name = ucwords($name);

        // remove spaces
        $name = str_replace(' ', '', $name);

        return $name.$classSuffix;
    }
}
