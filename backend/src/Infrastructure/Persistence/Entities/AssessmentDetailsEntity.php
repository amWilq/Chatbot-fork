<?php

namespace App\Infrastructure\Persistence\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'assessment_details')]
class AssessmentDetailsEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'assessment_details_id', type: 'integer')]
    private int $id;
    #[ORM\JoinColumn(name: 'assessment_id', referencedColumnName: 'assessment_id')]
    #[ORM\OneToOne(targetEntity: AssessmentEntity::class)]
    private AssessmentEntity $assessment;
    #[ORM\JoinColumn(name: 'assessment_type_id', referencedColumnName: 'assessment_type_id')]
    #[ORM\ManyToOne(targetEntity: AssessmentTypeEntity::class)]
    private AssessmentTypeEntity $assessmentType;
    #[ORM\Column(type: 'json')]
    private array $assessmentDetails;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAssessment(): AssessmentEntity
    {
        return $this->assessment;
    }

    public function setAssessment(AssessmentEntity $assessment): void
    {
        $this->assessment = $assessment;
    }

    public function getAssessmentType(): AssessmentTypeEntity
    {
        return $this->assessmentType;
    }

    public function setAssessmentType(AssessmentTypeEntity $assessmentType): void
    {
        $this->assessmentType = $assessmentType;
    }

    public function getAssessmentDetails(): array
    {
        return $this->assessmentDetails;
    }

    public function setAssessmentDetails(array $assessmentDetails): void
    {
        $this->assessmentDetails = $assessmentDetails;
    }

    public function __construct(string $assessmentId, string $assessmentTypeId, array $assessmentDetails)
    {
        $this->setAssessment($assessmentId);
        $this->setAssessmentType($assessmentTypeId);
        $this->setAssessmentDetails($assessmentDetails);
    }

}
