<?php

namespace App\Application\Dtos;

use App\Domain\Category\Entities\Category;
use App\Shared\Models\EntityToArrayInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class CategoryDTO implements EntityToArrayInterface
{

    private function __construct(
        private string $id,
        private string $name,
        private string $iconUrl,
    ) {
    }

    protected function getId(): string
    {
        return $this->id;
    }

    protected function getName(): string
    {
        return $this->name;
    }

    protected function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    public static function fromDomainEntity(Category $category): self
    {
        return new self(
            id: $category->getId()->toString(),
            name: $category->getName(),
            iconUrl: $category->getIconUrl()
        );
    }

    #[ArrayShape([
        'categoryId' => 'string',
        'name' => 'string',
        'icon' => 'string',
    ])]
    public function toArray(): array
    {
        return [
            'categoryId' => $this->getId(),
            'name' => $this->getName(),
            'icon' => $this->getIconUrl(),
        ];
    }

}
