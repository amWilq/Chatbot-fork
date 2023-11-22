<?php

namespace App\Shared\Models;

abstract class AggregateRoot
{

    protected ValueObject $id;

    public function __construct(ValueObject $id)
    {
        $this->id = $id;
    }

    public function getId(): ValueObject
    {
        return $this->id;
    }
}
