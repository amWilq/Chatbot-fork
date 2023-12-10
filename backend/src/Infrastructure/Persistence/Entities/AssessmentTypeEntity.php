<?php

namespace App\Infrastructure\Persistence\Entities;

use App\Domain\Assessment\Entities\AssessmentType;
use App\Infrastructure\Persistence\Repository\AssessmentTypeEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssessmentTypeEntityRepository::class)]
#[ORM\Table(name: 'assessment_types')]
class AssessmentTypeEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'assessment_type_id', type: Types::STRING)]
    private string $id;
    #[ORM\Column(name: 'name', type: Types::STRING)]
    private string $name;
    #[ORM\Column(name: 'description', type: Types::STRING)]
    private string $description;
    #[ORM\Column(name: 'difficulties', type: Types::JSON)]
    private array $difficulties;
    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id):void
    {
        $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name):void
    {
        $this->name = $name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description):void
    {
        $this->description = $description;
    }
    public function getDifficulties(): array
    {
        return $this->difficulties;
    }
    public function setDifficulties(array $difficulties): self
    {
        $this->difficulties = $difficulties;
        return $this;
    }
    public static function fromDomainEntity(AssessmentType $assessmentType): self
    {
        $assessmentTypeEntity = new self();

        $assessmentTypeEntity->setId($assessmentType->getId()->toString());
        $assessmentTypeEntity->setName($assessmentType->getName());
        $assessmentTypeEntity->setDescription($assessmentType->getDescription());
        $assessmentTypeEntity->setDifficulties($assessmentType->getDifficulties());

        return $assessmentTypeEntity;
    }
}
