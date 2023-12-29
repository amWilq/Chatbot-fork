<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\ValueObject;

readonly class CodeSnippet extends ValueObject
{
    private function __construct(
        private string $code,
        private string $correctSolution
    ) {}

    public static function create(
        string $code,
        string $correctSolution
    ) : self {
        return new self($code, $correctSolution);
    }

    public function getCode(): string {
        return $this->code;
    }

    public function getCorrectSolution(): string {
        return $this->correctSolution;
    }

    public function equals(ValueObject $object): bool
    {
        if (!$object instanceof self) {
            return false;
        }

        return $this->code === $object->code
            && $this->correctSolution === $object->correctSolution;
    }
}
