<?php

namespace App\Domain\Assessment\ValueObjects;

use App\Shared\Models\ValueObject;

readonly class Message extends ValueObject
{
    private function __construct(
        private string $sender,
        private string $message
    ) {}

    public static function create(
        string $sender,
        string $message
    ) : self {
        return new self($sender, $message);
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function equals(ValueObject $object): bool
    {
        if (!$object instanceof self) {
            return false;
        }

        return $this->sender === $object->sender
            && $this->message === $object->message;
    }
}
