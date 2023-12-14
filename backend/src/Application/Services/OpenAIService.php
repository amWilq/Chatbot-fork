<?php

namespace App\Application\Services;

use App\Domain\Assessment\Entities\Assessment;
use App\Infrastructure\OpenAI\ApiClientInterface;

class OpenAIService implements OpenAIServiceInterface
{
    public function __construct(
        private readonly ApiClientInterface $apiClient,
    ) {

    }

    public function generateProblem(string $assessmentTypeName, array $data): array
    {
        return [];
    }

    public function handleAnswer(Assessment $assessment, array $data): array
    {
        return [];
    }

    public function adjustDifficulty(Assessment $assessment): array
    {
        return [];
    }

    public function generateFeedback(Assessment $assessment): array
    {
        return [];
    }

}
