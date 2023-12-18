<?php

namespace App\Application\Services;

use App\Domain\Assessment\Entities\Assessment;

interface OpenAIServiceInterface
{
    public function generateProblem(Assessment $assessment, object $data): array;

    public function handleAnswer(Assessment $assessment, object $data): array;
    public function getGeneratedFeedback(Assessment $assessment): array;
}
