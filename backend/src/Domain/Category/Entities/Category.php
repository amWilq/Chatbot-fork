<?php

namespace App\Domain\Category\Entities;

use App\Shared\Models\AggregateRoot;
use App\Domain\Category\ValueObjects\CategoryId;

class Category extends AggregateRoot
{
    private string $name;

    public function __construct(CategoryId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

}
