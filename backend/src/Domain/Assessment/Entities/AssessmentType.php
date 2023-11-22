<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\ValueObjects\AssessmentTypeId;

class AssessmentType
{

    protected AssessmentTypeId $id;

    protected string $name;

    /** @var \App\Domain\Assessment\Enums\DifficultiesEnum[]  */
    protected array $difficulties;

    protected FormatEnum $format;

    public function __construct(AssessmentTypeId $id, string $name, array $difficulties, FormatEnum $format)
    {
        $this->id = $id;
        $this->name = $name;
        $this->difficulties = $difficulties;
        $this->format = $format;
    }


}
