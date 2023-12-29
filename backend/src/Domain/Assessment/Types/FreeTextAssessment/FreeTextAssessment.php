<?php

namespace App\Domain\Assessment\Types\FreeTextAssessment;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\ValueObjects\Message;

final class FreeTextAssessment extends AssessmentType
{
    /** @var Message[] */
    protected array $messages = [];

    /**
     * Class constructor.
     */
    private function __construct(
        ?string $id,
        array $messages,
    ) {
        parent::__construct(
            id: $id,
            formatName: FormatEnum::FREE_TEXT->value
        );
        $this->messages = $messages;
    }

    public static function create(
        string $id = null,
        array $messages = [],
    ): self {
        return new self(
            id: $id,
            messages: $messages,
        );
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    public function getAssessmentType(): AssessmentType
    {
        return $this->getParent();
    }
}
