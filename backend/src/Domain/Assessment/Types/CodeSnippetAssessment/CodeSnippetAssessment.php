<?php

namespace App\Domain\Assessment\Types\CodeSnippetAssessment;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\FormatEnum;

final class CodeSnippetAssessment extends AssessmentType
{
    protected int $snippetCount;

    protected int $correctSnippetCount;

    /** @var CodeSnippetAttempt[] */
    protected array $snippetAttempts;

    private function __construct(
        ?string $id,
        int $snippetCount,
        int $correctSnippetCount,
        array $snippetAttempts,
    ) {
        parent::__construct(
            id: $id,
            formatName: FormatEnum::CODE_SNIPPET->value
        );
        $this->snippetCount = $snippetCount;
        $this->correctSnippetCount = $correctSnippetCount;
        $this->snippetAttempts = $snippetAttempts;
    }

    public static function create(
        string $id = null,
        int $snippetCount = 0,
        int $correctSnippetCount = 0,
        array $snippetAttempts = [],
    ): self {
        return new self(
            id: $id,
            snippetCount: $snippetCount,
            correctSnippetCount: $correctSnippetCount,
            snippetAttempts: $snippetAttempts,
        );
    }

    public function getSnippetCount(): int
    {
        return $this->snippetCount;
    }

    public function getCorrectSnippetCount(): int
    {
        return $this->correctSnippetCount;
    }

    public function getSnippetAttempts(): array
    {
        return $this->snippetAttempts;
    }

    public function checkAndIncreaseCorrectSnippets(CodeSnippetAttempt $snippetAttempt): void
    {
        if ($snippetAttempt->isCorrect()) {
            ++$this->correctSnippetCount;
        }
    }

    public function setSnippetAttempts(
        CodeSnippetAttempt $snippetAttempt
    ): void {
        $this->snippetAttempts[] = $snippetAttempt;
        ++$this->snippetCount;
    }

    public function getAssessmentType(): AssessmentType
    {
        return $this->getParent();
    }
}
