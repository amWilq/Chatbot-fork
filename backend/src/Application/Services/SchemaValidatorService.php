<?php

namespace App\Application\Services;

use JsonSchema\Exception\JsonDecodingException;
use JsonSchema\Validator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Console\Logger\ConsoleLogger;

class SchemaValidatorService
{
    private const SCHEMAS_DIR = '%s/resources/schemas/%s.json';
    private ConsoleLogger $consoleLogger;
    private ConsoleOutput $output;

    public function __construct(
        private readonly Validator $validator,
        private readonly string $projectDir,
    ) {
        $this->output = new ConsoleOutput();
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

    /**
     * @throws JsonDecodingException|BadRequestException
     */
    public function validateRequestSchema(object $postData, string $schemaName): void
    {
        try {
            $isValid = $this->validateRequestBody($postData, $schemaName);
        } catch (\JsonException $e) {
            $this->consoleLogger->error(
                print_r($this->validator->getErrors(), true)
            );
            throw new JsonDecodingException("Unsuccessful json decode: $e");
        }

        if (!$isValid) {
            $this->consoleLogger->error(
                print_r($this->validator->getErrors(), true)
            );
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
            $requestBody,
            json_decode(file_get_contents($schema), false, 512, JSON_THROW_ON_ERROR)
        );

        return $this->validator->isValid();
    }
}
