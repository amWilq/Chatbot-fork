<?php

namespace App\Domain\Assessment\Enums;

enum DifficultiesEnum: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
}
