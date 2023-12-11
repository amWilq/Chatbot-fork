<?php

namespace App\Application\Services;

use App\Application\Dtos\AssessmentStartDTO;
use JsonSchema\Exception\JsonDecodingException;
use JsonSchema\Validator;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AssessmentService
{
    private const SCHEMAS_DIR = '%s/resources/schemas/%s';
    private const ASSESSMENT_START_SCHEMA = 'AssessmentStartRequest.json';

    public function __construct(
        private readonly Validator $validator,
        private readonly string $projectDir
    ) {
    }

    /**
     * @throws BadRequestException|JsonDecodingException|
     */
    public function startAssessment(object $postData): array
    {
        try {
            $isValid = $this->validateRequestBody($postData, self::ASSESSMENT_START_SCHEMA);
        } catch (\JsonException $e) {
            throw new JsonDecodingException("Unsuccessful json decode: $e");
        }

        if (!$isValid) {
            throw new BadRequestException("Request body doesn't meet schema!");
        }



        return AssessmentStartDTO::create(
            assessmentState: '',
            assessmentId: '',
            assessmentTypeId: '',
            startTime: '',
            languageId: '',
            difficulty: '',
            categoryId: '',
        )->toArray();
    }

    /**
     * @throws \JsonException
     */
    private function validateRequestBody(object $requestBody, string $schemaName): bool
    {
        $schema = sprintf(self::SCHEMAS_DIR, $this->projectDir, $schemaName);
        $this->validator->validate($postData,
            json_decode(file_get_contents($schema), false, 512, JSON_THROW_ON_ERROR)
        );

        return $this->validator->isValid();
    }
}
