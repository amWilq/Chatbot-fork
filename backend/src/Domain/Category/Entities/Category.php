<?php

namespace App\Domain\Category\Entities;

use App\Shared\Models\AggregateRoot;
use App\Domain\Category\ValueObjects\CategoryId;
use JetBrains\PhpStorm\ArrayShape;

class Category extends AggregateRoot
{
    private function __construct(
        protected string $name,
        protected string $iconUrl,
    ) {
        $this->id = CategoryId::create(AggregateRoot::generateId());
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(string $name, string $iconUrl): self
    {
        return new self(
            name: $name,
            iconUrl: $iconUrl
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


}
