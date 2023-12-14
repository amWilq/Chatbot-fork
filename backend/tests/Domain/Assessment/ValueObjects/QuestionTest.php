<?php

namespace App\Tests\Domain\Assessment\ValueObjects;

use App\Domain\Assessment\ValueObjects\Question;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{

    public function testQuestionCreation(): void
    {
        $content = "What is the capital of France?";
        $options = ["Paris", "London", "Berlin", "Rome"];
        $correctAnswer = "Paris";

        $question = new Question($content, $options, $correctAnswer);

        $this->assertSame($content, $question->getContent());
        $this->assertSame($options, $question->getOptions());
        $this->assertSame($correctAnswer, $question->getCorrectAnswer());
    }

    public function testEquals(): void
    {
        $question1 = new Question("What is the capital of France?", ["Paris", "London", "Berlin", "Rome"], "Paris");
        $question2 = new Question("What is the capital of France?", ["Paris", "London", "Berlin", "Rome"], "Paris");
        $question3 = new Question("What is the capital of Germany?", ["Paris", "London", "Berlin", "Rome"], "Berlin");

        $this->assertTrue($question1->equals($question2));
        $this->assertFalse($question1->equals($question3));
    }


}
