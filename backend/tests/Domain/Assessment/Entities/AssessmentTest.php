<?php

namespace App\Tests\Domain\Assessment\Entities;

use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\AssessmentStatusEnum;
use App\Domain\Assessment\Enums\DifficultiesEnum;
use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use App\Domain\Category\ValueObjects\CategoryId;
use App\Domain\Language\ValueObjects\LanguageId;
use App\Domain\User\ValueObjects\UserDeviceId;
use PHPUnit\Framework\TestCase;

final class AssessmentTest extends TestCase
{
    private Assessment $assessment;
    private AssessmentType $assessmentType;

    protected function setUp(): void
    {
        parent::setUp();

        $userDeviceId = UserDeviceId::create('user_device_id');
        $categoryId = CategoryId::create('category_id');
        $languageId = LanguageId::create('language_id');
        $difficulty = DifficultiesEnum::BEGINNER->value;
        $this->assessmentType = QuizAssessment::create(500);

        $this->assessment = Assessment::create($userDeviceId, $categoryId, $languageId, $difficulty, $this->assessmentType);
    }

    /**
     * Test create method
     */
    public function test_assessment_can_be_created(): void
    {
        self::assertInstanceOf(Assessment::class, $this->assessment);
    }

    /**
     * Test get difficulty at start
     */
    public function test_can_get_difficulty_at_start(): void
    {
        self::assertEquals(DifficultiesEnum::BEGINNER, $this->assessment->getDifficultyAtStart());
    }

    /**
     * Test get status
     */
    public function test_can_get_status(): void
    {
        self::assertEquals(AssessmentStatusEnum::ASSESSMENT_START_SUCCESS, $this->assessment->getStatus());
    }

    /**
     * Test get user device id
     */
    public function test_can_get_user_device_id(): void
    {
        self::assertEquals(UserDeviceId::create('user_device_id'), $this->assessment->getUserDeviceId());
    }

    /**
     * Test get category id
     */
    public function test_can_get_category_id(): void
    {
        self::assertEquals(CategoryId::create('category_id'), $this->assessment->getCategoryId());
    }

    /**
     * Test get language id
     */
    public function test_can_get_language_id(): void
    {
        self::assertEquals(LanguageId::create('language_id'), $this->assessment->getLanguageId());
    }

    /**
     * Test get assessment type
     */
    public function test_can_get_assessment_type(): void
    {
        self::assertNotEquals(QuizAssessment::create(500), $this->assessment->getAssessmentType());
    }
}
