<?php

namespace App\Tests\Shared\Traits;

use App\Shared\Traits\HelperTrait;
use PHPUnit\Framework\TestCase;

class HelperTraitTest extends TestCase
{
    use HelperTrait;

    public function testArraysAreEqual(): void
    {
        $array = ["1", "2", "3"];
        $array2 = ["3", "2", "1"];
        $this->assertTrue($this->arraysAreEqual($array, $array2));

        $array2 = [3, 2, 1];
        $this->assertTrue($this->arraysAreEqual($array, $array2));
    }
}
