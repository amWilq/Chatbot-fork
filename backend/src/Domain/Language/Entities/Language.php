<?php

namespace App\Domain\Language\Entities;

use App\Domain\Category\Entities\Category;
use App\Domain\Language\ValueObjects\LanguageId;
use App\Shared\Models\AggregateRoot;
use JetBrains\PhpStorm\ArrayShape;

class Language extends AggregateRoot
{

    /**
     * Language constructor.
     */
    private function __construct(
      string $id,
      protected string $name,
      protected string $iconUrl,
      protected array $categories,
    ) {
        $this->id = LanguageId::create($id) ??
          LanguageId::create(AggregateRoot::generateId());
    }

    /**
     * Create a new instance of the current object.
     */
    public static function create(
      string $name,
      string $iconUrl,
      array $categories,
      string $id = null
    ): self {
        return new self(
          id: $id,
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

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

}
