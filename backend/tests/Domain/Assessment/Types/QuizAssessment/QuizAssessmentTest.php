<?php

namespace App\Tests\Domain\Assessment\Types\QuizAssessment;

use App\Domain\Assessment\Enums\DifficultiesEnum;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use App\Domain\Assessment\Types\QuizAssessment\QuestionAttempt;
use PHPUnit\Framework\TestCase;

final class QuizAssessmentTest extends TestCase
{
    private QuizAssessment $quizAssessment;
    private QuestionAttempt $questionAttempt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->quizAssessment = QuizAssessment::create(500);
        $this->questionAttempt = $this->createMock(QuestionAttempt::class);
    }

    public function testSetQuestionsAttemptsIncreasesTheQuestionCountCorrectly(): void
    {
        $this->quizAssessment->setQuestionsAttempts($this->questionAttempt);

        $quizAssessmentQuestionCount = $this->quizAssessment->getQuestionCount();
        $this->assertEquals(1, $quizAssessmentQuestionCount);

        for ($i = 2; $i <= 5; $i++) {
            $this->quizAssessment->setQuestionsAttempts($this->questionAttempt);
            $quizAssessmentQuestionCount = $this->quizAssessment->getQuestionCount();
            $this->assertEquals($i, $quizAssessmentQuestionCount);
        }
    }

    public function testCorrectAnswerCountWhenAllAnswersAreIncorrect(): void
    {
        $this->questionAttempt->method('isCorrect')->willReturn(false);

        for ($i = 0; $i < 3; $i++) {
            $this->quizAssessment->setQuestionsAttempts($this->questionAttempt);
        }

        $this->assertEquals(0, $this->quizAssessment->getCorrectAnswerCount());
    }

    public function testCorrectAnswerCountWhenSomeAnswersAreCorrect(): void
    {
        $this->questionAttempt->method('isCorrect')->willReturnOnConsecutiveCalls(true, false, true);

        for ($i = 0; $i < 3; $i++) {
            $this->quizAssessment->setQuestionsAttempts($this->questionAttempt);
        }

        $this->assertEquals(2, $this->quizAssessment->getCorrectAnswerCount());
    }

    public function testGetDuration(): void
    {
        $this->assertEquals(500, $this->quizAssessment->getDuration());
    }

    public function testParentFormatDataCorrectness(): void
    {
        $this->assertEquals('quiz', $this->quizAssessment->getName());
        $this->assertEquals(
          'A quick and informal assessment of student knowledge.',
          $this->quizAssessment->getDescription()
        );
        $this->assertEquals([DifficultiesEnum::BEGINNER],
          $this->quizAssessment->getDifficulties());
    }


}
