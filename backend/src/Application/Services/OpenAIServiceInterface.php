<?php

namespace App\Application\Services;

use App\Domain\Assessment\Entities\Assessment;

interface OpenAIServiceInterface
{
    public function generateProblem(string $assessmentTypeName, array $data): array;

    public function handleAnswer(Assessment $assessment, array $data): array;

    public function adjustDifficulty(Assessment $assessment): array;

    public function generateFeedback(Assessment $assessment): array;
}
