<?php

namespace App\Domain\Assessment\Enums;

enum FormatEnum: string
{
    case QUIZ = 'quiz';
    case FREE_TEXT = 'free-text';
    case MULTIPLE_CHOICE = 'multiple-choice';
    case CODE_SNIPPET = 'code-snippet';

    public function getDescription(): string
    {
        return match ($this) {
            self::QUIZ => 'A quick and informal assessment of student knowledge.',
            self::FREE_TEXT => 'An open-ended question that is also known as essay, free format, or comments',
            self::MULTIPLE_CHOICE => 'An assessment item consisting of a stem,
            which poses the question or problem, followed by a list of possible responses',
            self::CODE_SNIPPET => 'Small blocks of code will be presented with
            purpose to find errors or to complete the code.',
        };
    }

    public function getDifficulties(): array
    {
        return match ($this) {
            self::QUIZ, self::MULTIPLE_CHOICE => [DifficultiesEnum::BEGINNER],
            self::FREE_TEXT => [DifficultiesEnum::BEGINNER, DifficultiesEnum::INTERMEDIATE, DifficultiesEnum::ADVANCED],
            self::CODE_SNIPPET => [DifficultiesEnum::INTERMEDIATE, DifficultiesEnum::ADVANCED],
        };
    }
}
