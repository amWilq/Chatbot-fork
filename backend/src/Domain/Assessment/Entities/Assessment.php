<?php

namespace App\Domain\Assessment\Entities;

use App\Domain\Assessment\ValueObjects\AssessmentId;
use App\Shared\Models\AggregateRoot;

class Assessment extends AggregateRoot
{
    protected \DateTime $startTime;
    protected \DateTime $endTime;
    protected string $feedback;
    protected AssessmentType $assessmentType;

    public function __construct(AssessmentId $id)
    {
        parent::__construct($id);

    }

}
