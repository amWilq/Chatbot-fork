<?php

namespace App\Application\Services;

interface SchemaValidatorServiceInterface
{
    public function validateRequestSchema(object $postData, string $schemaName): void;
}
