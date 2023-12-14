<?php

namespace App\Application\Services;

use JsonSchema\Exception\JsonDecodingException;
use Psr\Log\LogLevel;
use Swaggest\JsonSchema\Schema;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SchemaValidatorService
{
    private const SCHEMAS_DIR = '%s/resources/schemas/%s.json';
    private ConsoleLogger $consoleLogger;
    private ConsoleOutput $output;

    public function __construct(
        private readonly Schema $validator,
        private readonly string $projectDir,
    ) {
        $this->output = new ConsoleOutput(OutputInterface::VERBOSITY_DEBUG);
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
            $this->consoleLogger->log(LogLevel::CRITICAL, print_r($e->getMessage(), true));
            throw new JsonDecodingException(JSON_ERROR_NONE, $e->getPrevious());
        }

        if (!$isValid) {
            throw new BadRequestException("Request body doesn't meet schema!");
        }
    }

    /**
     * @throws \Exception
     */
    protected function validateRequestBody(object $requestBody, string $schemaName): bool
    {
        $schema = sprintf(self::SCHEMAS_DIR, $this->projectDir, $schemaName);
        try {
            $validator = Schema::import(
                json_decode(
                    file_get_contents($schema), false, 512, JSON_THROW_ON_ERROR)
                );
            $validator->in(
                $requestBody
            );
        } catch (\Exception $e) {
            throw new \JsonException($e->getMessage(), 5001, $e->getPrevious());
        }

        return true;
    }
}
