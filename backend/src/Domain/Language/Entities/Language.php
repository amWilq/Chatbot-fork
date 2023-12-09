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
        protected array $categories,
    ) {
        $this->id = LanguageId::create(AggregateRoot::generateId());
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(string $name, string $iconUrl, array $categories): self
    {
        return new self(
            name: $name,
            iconUrl: $iconUrl,
            categories: $categories
        );
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }
    public function getCategories(): array
    {
        return $this->categories;
    }
}
