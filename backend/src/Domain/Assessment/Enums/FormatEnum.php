<?php

namespace App\Domain\Assessment\Enums;

enum FormatEnum: string
{
    case QUIZ = 'quiz';
    case FREE_TEXT = 'free text';
    case MULTIPLE_CHOICE = 'multiple choice';
    case CODE_SNIPPET = 'code snippet';
}
