<?php

namespace App\Domain\Language\Entities;

use App\Domain\Language\ValueObjects\LanguageId;
use App\Shared\Models\AggregateRoot;
use JetBrains\PhpStorm\ArrayShape;

class Language extends AggregateRoot
{
    /**
     * Language constructor.
     */
    private function __construct(
        protected string $name,
        protected string $iconUrl,
        protected array $categoryIds,
    ) {
        $this->id = LanguageId::create(AggregateRoot::generateId());
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(string $name, string $iconUrl, array $categoryIds): self
    {
        return new self(
            name: $name,
            iconUrl: $iconUrl,
            categoryIds: $categoryIds
        );
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'id' => "string",
        'name' => "string",
        'iconUrl' => "string",
        'categoryIds' => "array"
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->getId()->toString(),
            'name' => $this->name,
            'iconUrl' => $this->iconUrl,
            'categoryIds' => $this->categoryIds
        ];
    }

}
