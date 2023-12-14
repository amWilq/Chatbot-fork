<?php

namespace App\Tests\Domain\Assessment\Types\QuizAssessment;

use PHPUnit\Framework\TestCase;
use App\Domain\Assessment\Types\QuizAssessment\QuestionAttempt;
use App\Domain\Assessment\ValueObjects\Question;

/**
 * Class QuestionAttempt2Test
 * Test case for testing the __construct of QuestionAttempt class.
 */
class QuestionAttemptTest extends TestCase
{
    private Question $question;
    private QuestionAttempt $questionAttempt;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->question = Question::create('Question 1.', ['Option A', 'Option B', 'Option C'], 0, 'Option A is correct, because...');
        $this->questionAttempt = QuestionAttempt::create($this->question);
    }

    /**
     * Test if the QuestionAttempt instance is created with correct Question instance.
     */
    public function testCreateQuestionAttempt(): void
    {
        $this->assertEquals($this->question, $this->questionAttempt->getQuestion());
    }

    /**
     * Test if the user answer and taken time can be correctly set and retrieved.
     */
    public function testSetAndGetAnswer(): void
    {
        $this->questionAttempt->setAnswer(2, 100);
        $this->assertEquals(2, $this->questionAttempt->getAnswer());
        $this->assertEquals(100, $this->questionAttempt->getTakenTime());
    }

    /**
     * Test if the isCorrect status can be correctly evaluated and retrieved.
     */
    public function testCheckAnswerCorrectness(): void
    {
        $this->questionAttempt->setAnswer(0, 100);
        $this->assertTrue($this->questionAttempt->checkAnswerCorrectness());
        $this->assertTrue($this->questionAttempt->isCorrect());
    }

    /**
     * Test if checkAnswerCorrectness returns false when the answer is not correct.
     */
    public function testCheckIncorrectAnswer(): void
    {
        $this->questionAttempt->setAnswer(2, 100);
        $this->assertFalse($this->questionAttempt->checkAnswerCorrectness());
        $this->assertFalse($this->questionAttempt->isCorrect());
    }

    /**
     * Tests whether the question object associated with the question attempt object is equal to the given question object.
     */
    public function testObjectEqual(): void
    {
        $this->assertTrue(
          $this->questionAttempt->getQuestion()->equals($this->question)
        );
    }
}
