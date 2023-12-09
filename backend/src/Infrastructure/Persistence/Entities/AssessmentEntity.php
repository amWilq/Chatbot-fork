<?php

namespace App\Infrastructure\Persistence\Entities;

use App\Domain\Assessment\Entities\Assessment;
use App\Infrastructure\Persistence\Repository\AssessmentEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'assessment')]
#[ORM\Entity(repositoryClass: AssessmentEntityRepository::class)]
class AssessmentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'assessment_id', type: Types::STRING)]
    private string $id;

    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\OneToMany(targetEntity: UserEntity::class)]
    private UserEntity $user;

    #[ORM\JoinColumn(name: 'language_id', referencedColumnName: 'language_id')]
    #[ORM\ManyToOne(targetEntity: LanguageEntity::class)]
    private LanguageEntity $language;

    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'category_id')]
    #[ORM\ManyToOne(targetEntity: CategoryEntity::class)]
    private CategoryEntity $category;

    #[ORM\JoinColumn(name: 'assessment_details_id', referencedColumnName: 'assessment_details_id')]
    #[ORM\OneToOne(targetEntity: AssessmentDetailsEntity::class)]
    private AssessmentDetailsEntity $assessmentDetails;

    #[ORM\Column(name: 'status', type: Types::STRING)]
    private string $status;

    #[ORM\Column(name: 'start_time', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTime $startTime;

    #[ORM\Column(name: 'end_time', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTime $endTime;

    #[ORM\Column(name: 'start_difficulty', type: Types::STRING)]
    private string $startDifficulty;

    #[ORM\Column(name: 'current_difficulty', type: Types::STRING)]
    private string $currentDifficulty;

    #[ORM\Column(name: 'end_difficulty', type: Types::STRING)]
    private string $endDifficulty;

    #[ORM\Column(name: 'feedback', type: Types::STRING)]
    private string $feedback;
    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id): void
    {
        $this->id = $id;
    }
    public function getUser(): UserEntity
    {
        return $this->user;
    }
    public function setUser(UserEntity $user):void
    {
        $this->user = $user;
    }
    public function getLanguage(): LanguageEntity
    {
        return $this->language;
    }
    public function setLanguage(LanguageEntity $language):void
    {
        $this->language = $language;
    }
    public function getCategory(): CategoryEntity
    {
        return $this->category;
    }
    public function setCategory(CategoryEntity $category):void
    {
        $this->category = $category;
    }
    public function getAssessmentDetails(): AssessmentDetailsEntity
    {
        return $this->assessmentDetails;
    }
    public function setAssessmentDetails(AssessmentDetailsEntity $assessmentDetails):void
    {
        $this->assessmentDetails = $assessmentDetails;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function setStatus(string $status):void
    {
        $this->status = $status;
    }
    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }
    public function setStartTime(\DateTime $startTime):void
    {
        $this->startTime = $startTime;
    }
    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }
    public function setEndTime(\DateTime $endTime):void
    {
        $this->endTime = $endTime;
    }
    public function getStartDifficulty(): string
    {
        return $this->startDifficulty;
    }
    public function setStartDifficulty(string $startDifficulty):void
    {
        $this->startDifficulty = $startDifficulty;
    }
    public function getCurrentDifficulty(): string
    {
        return $this->currentDifficulty;
    }
    public function setCurrentDifficulty(string $currentDifficulty):void
    {
        $this->currentDifficulty = $currentDifficulty;
    }
    public function getEndDifficulty(): string
    {
        return $this->endDifficulty;
    }
    public function setEndDifficulty(string $endDifficulty):void
    {
        $this->endDifficulty = $endDifficulty;
    }
    public function getFeedback(): string
    {
        return $this->feedback;
    }
    public function setFeedback(string $feedback):void
    {
        $this->feedback = $feedback;
    }

    public static function fromDomainEntity(Assessment $assessment, array $assessmentDetails): self
    {
        $assessmentEntity = new self();

        $assessmentEntity->setId($assessment->getId()->toString());
        $assessmentEntity->setUser(UserEntity::fromDomainEntity($assessment->getUser()));
        $assessmentEntity->setCategory(CategoryEntity::fromDomainEntity($assessment->getCategory()));
        $assessmentEntity->setLanguage(LanguageEntity::fromDomainEntity($assessment->getLanguage()));
        $assessmentEntity->setAssessmentDetails(
            new AssessmentDetailsEntity(
                $assessment->getId()->toString(),
                $assessment->getAssessmentType()->getId()->toString(),
                $assessmentDetails
            )
        );
        $assessmentEntity->setStatus($assessment->getStatus()->name);
        $assessmentEntity->setStartTime($assessment->getStartTime());
        $assessmentEntity->setEndTime($assessment->getEndTime());
        $assessmentEntity->setStartDifficulty($assessment->getDifficultyAtStart());
        $assessmentEntity->setCurrentDifficulty($assessment->getCurrentDifficulty());
        $assessmentEntity->setEndDifficulty($assessment->getDifficultyAtEnd());
        $assessmentEntity->setFeedback($assessment->getFeedback());
        
        return $assessmentEntity;
    }

}
