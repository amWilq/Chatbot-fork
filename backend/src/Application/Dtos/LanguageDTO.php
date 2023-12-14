<?php

namespace App\Application\Dtos;

use App\Domain\Language\Entities\Language;
use App\Shared\Models\EntityToArrayInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
readonly class LanguageDTO implements EntityToArrayInterface
{

    private function __construct(
        private string $id,
        private string $name,
        private string $iconUrl,
        private array $categoryIds,
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

    protected function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    public static function fromDomainEntity(Language $language): self
    {
        $categoryIds = [];

        foreach ($language->getCategories() as $category) {
            $categoryIds[] = $category->getId()->toString();
        }

        return new self(
            id: $language->getId()->toString(),
            name: $language->getName(),
            iconUrl: $language->getIconUrl(),
            categoryIds: $categoryIds
        );
    }

    #[ArrayShape([
        'languageId' => 'string',
        'name' => 'string',
        'icon' => 'string',
        'categoryIds' => 'array',
    ])]
    public function toArray(): array
    {
        return [
            'languageId' => $this->getId(),
            'name' => $this->getName(),
            'icon' => $this->getIconUrl(),
            'categoryIds' => $this->getCategoryIds(),
        ];
    }

}
