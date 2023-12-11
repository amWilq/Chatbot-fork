<?php

namespace App\Domain\Category\Entities;

use App\Shared\Models\AggregateRoot;
use App\Domain\Category\ValueObjects\CategoryId;
use JetBrains\PhpStorm\ArrayShape;

class Category extends AggregateRoot
{

    private function __construct(
        ?string $id,
        protected string $name,
        protected string $iconUrl,
    ) {
        $this->id = $id ? CategoryId::create($id) :
            CategoryId::create(AggregateRoot::generateId());
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(string $name, string $iconUrl, string $id = null): self
    {
        return new self(
            id: $id,
            name: $name,
            iconUrl: $iconUrl,
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
