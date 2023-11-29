<?php

namespace App\Tests\Domain\Assessment\Entities;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\FormatEnum;
use PHPUnit\Framework\TestCase;

class AssessmentTypeTest extends TestCase
{

    /**
     * Tests the `getFormatName` method of the AssessmentType class.
     */
    public function testGetFormatName()
    {
        $formatName = FormatEnum::QUIZ->value;
        $assessmentType = new \ReflectionClass(AssessmentType::class);
        $instance = $assessmentType->newInstanceWithoutConstructor();
        $constructor = $assessmentType->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($instance, $formatName);

        $this->assertEquals($formatName, $instance->getName());
    }

    /**
     * Tests the `getId` method of the AssessmentType class.
     */
    public function testGetId()
    {
        $formatName = FormatEnum::QUIZ->value;
        $assessmentType = new \ReflectionClass(AssessmentType::class);
        $instance = $assessmentType->newInstanceWithoutConstructor();
        $constructor = $assessmentType->getConstructor();
        $constructor->setAccessible(true);
        $constructor->invoke($instance, $formatName);

        $this->assertInstanceOf('App\Domain\Assessment\ValueObjects\AssessmentTypeId', $instance->getId());
    }

    // Similarly, you can continue adding more tests for additional methods.
}
