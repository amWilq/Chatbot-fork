<?php

namespace App\Application\Services;

use App\Domain\Assessment\Entities\Assessment;

interface AssessmentServiceInterface
{
    public function startAssessment(object $postData, string $assessmentTypeName): array;

    public function interactAssessment(object $data): array;

    public function completeAssessment(object $postData, array $pathParams): array;

    public function setAssessment(Assessment $assessment): void;

    public function getAssessment(): Assessment;

    public static function initAssessment(Assessment $assessment): void;

}
