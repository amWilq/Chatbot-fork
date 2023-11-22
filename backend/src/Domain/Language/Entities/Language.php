<?php

namespace App\Domain\Language\Entities;

use App\Domain\Language\ValueObjects\LanguageId;
use App\Shared\Models\AggregateRoot;

class Language extends AggregateRoot
{
    protected string $name;

    /** @var \App\Domain\Category\ValueObjects\CategoryId[] */
    protected array $categoryIds;

    /**
     * @param \App\Domain\Language\ValueObjects\LanguageId $id
     * @param string $name
     * @param \App\Domain\Category\ValueObjects\CategoryId[] $categoryIds
     */
    public function __construct(LanguageId $id, string $name, array $categoryIds)
    {
        parent::__construct($id);
        $this->name = $name;
        $this->categoryIds = $categoryIds;
    }

}
