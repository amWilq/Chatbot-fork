<?php

namespace App\Application\Services;

interface AssessmentTypeServiceInterface
{
    public function getAllAssessmentTypes(): array;

    public function getAssessmentTypeById(string $id): ?array;

}
