<?php

namespace App\Application\Services;

use JsonSchema\Exception\JsonDecodingException;
use JsonSchema\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SchemaValidatorService
{
    private const SCHEMAS_DIR = '%s/resources/schemas/%s.json';

    public function __construct(
        private readonly Validator $validator,
        private readonly string $projectDir,
    ) {
    }

    /**
     * @throws JsonDecodingException|BadRequestException
     */
    public function validateRequestSchema(object $postData, string $schemaName): void
    {
        try {
            $isValid = $this->validateRequestBody($postData, $schemaName);
        } catch (\JsonException $e) {
            throw new JsonDecodingException("Unsuccessful json decode: $e");
        }

        if (!$isValid) {
            throw new BadRequestException("Request body doesn't meet schema!");
        }
    }

    /**
     * @throws \JsonException
     */
    protected function validateRequestBody(object $requestBody, string $schemaName): bool
    {
        $schema = sprintf(self::SCHEMAS_DIR, $this->projectDir, $schemaName);
        $this->validator->validate(
            $postData,
            json_decode(file_get_contents($schema), false, 512, JSON_THROW_ON_ERROR)
        );

        return $this->validator->isValid();
    }
}
