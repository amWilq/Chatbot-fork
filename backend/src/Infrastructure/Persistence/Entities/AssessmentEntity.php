<?php

namespace App\Infrastructure\Persistence\Entities;

use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use App\Infrastructure\Persistence\Repository\AssessmentEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssessmentEntityRepository::class)]
#[ORM\Table(name: 'assessment')]
class AssessmentEntity implements PersistenceEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'assessment_id', type: Types::STRING)]
    private string $id;

    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[ORM\ManyToOne(targetEntity: UserEntity::class, cascade: ['persist'])]
    private UserEntity $user;

    #[ORM\JoinColumn(name: 'language_id', referencedColumnName: 'language_id')]
    #[ORM\ManyToOne(targetEntity: LanguageEntity::class, cascade: ['persist'])]
    private LanguageEntity $language;

    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'category_id')]
    #[ORM\ManyToOne(targetEntity: CategoryEntity::class, cascade: ['persist'])]
    private CategoryEntity $category;

    #[ORM\JoinColumn(name: 'assessment_details_id', referencedColumnName: 'assessment_details_id')]
    #[ORM\OneToOne(targetEntity: AssessmentDetailsEntity::class, cascade: ['persist'])]
    private AssessmentDetailsEntity $assessmentDetails;

    #[ORM\Column(name: 'status', type: Types::STRING)]
    private string $status;

    #[ORM\Column(name: 'start_time', type: Types::DATETIMETZ_MUTABLE)]
    private \DateTime $startTime;

    #[ORM\Column(name: 'end_time', type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTime $endTime;

    #[ORM\Column(name: 'start_difficulty', type: Types::STRING)]
    private string $startDifficulty;

    #[ORM\Column(name: 'current_difficulty', type: Types::STRING, nullable: true)]
    private ?string $currentDifficulty;

    #[ORM\Column(name: 'end_difficulty', type: Types::STRING, nullable: true)]
    private ?string $endDifficulty;

    #[ORM\Column(name: 'feedback', type: Types::STRING, nullable: true)]
    private ?string $feedback;

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

    public function setUser(UserEntity $user): void
    {
        $this->user = $user;
    }

    public function getLanguage(): LanguageEntity
    {
        return $this->language;
    }

    public function setLanguage(LanguageEntity $language): void
    {
        $this->language = $language;
    }

    public function getCategory(): CategoryEntity
    {
        return $this->category;
    }

    public function setCategory(CategoryEntity $category): void
    {
        $this->category = $category;
    }

    public function getAssessmentDetails(): AssessmentDetailsEntity
    {
        return $this->assessmentDetails;
    }

    public function setAssessmentDetails(AssessmentDetailsEntity $assessmentDetails): void
    {
        $this->assessmentDetails = $assessmentDetails;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTime $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getStartDifficulty(): string
    {
        return $this->startDifficulty;
    }

    public function setStartDifficulty(string $startDifficulty): void
    {
        $this->startDifficulty = $startDifficulty;
    }

    public function getCurrentDifficulty(): ?string
    {
        return $this->currentDifficulty;
    }

    public function setCurrentDifficulty(?string $currentDifficulty): void
    {
        $this->currentDifficulty = $currentDifficulty;
    }

    public function getEndDifficulty(): ?string
    {
        return $this->endDifficulty;
    }

    public function setEndDifficulty(?string $endDifficulty): void
    {
        $this->endDifficulty = $endDifficulty;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(?string $feedback): void
    {
        $this->feedback = $feedback;
    }

    public static function fromDomainEntity(
        Assessment $assessment,
        EntityManagerInterface $entityManager,
    ): self {
        $assessmentEntity = $entityManager->getRepository(AssessmentEntity::class)
            ->find($assessment->getId()->toString(), raw: true);

        $assessmentDetailsArray = self::extractAssessmentDetailsArray($assessment->getAssessmentType());

        if (is_null($assessmentEntity)) {
            $assessmentEntity = new self();
            $assessmentEntity->setId($assessment->getId()->toString());
            $assessmentEntity->setAssessmentDetails(
                new AssessmentDetailsEntity(
                    $assessmentEntity,
                    AssessmentTypeEntity::fromDomainEntity($assessment->getAssessmentType(), $entityManager),
                    $assessmentDetailsArray
                )
            );
        } else {
            $assessmentDetails = $assessmentEntity->getAssessmentDetails();
            $assessmentDetails->setAssessmentDetails($assessmentDetailsArray);
            $assessmentEntity->setAssessmentDetails($assessmentDetails);
        }

        $assessmentEntity->setUser(UserEntity::fromDomainEntity($assessment->getUser(), $entityManager));
        $assessmentEntity->setCategory(CategoryEntity::fromDomainEntity($assessment->getCategory(), $entityManager));
        $assessmentEntity->setLanguage(LanguageEntity::fromDomainEntity($assessment->getLanguage(), $entityManager));
        $assessmentEntity->setStatus($assessment->getStatus()->name);
        $assessmentEntity->setStartTime($assessment->getStartTime());
        $assessmentEntity->setEndTime($assessment->getEndTime());
        $assessmentEntity->setStartDifficulty($assessment->getDifficultyAtStart()->value);
        $assessmentEntity->setCurrentDifficulty($assessment->getCurrentDifficulty()?->value);
        $assessmentEntity->setEndDifficulty($assessment->getDifficultyAtEnd()?->value);
        $assessmentEntity->setFeedback($assessment->getFeedback());

        return $assessmentEntity;
    }

    public static function extractAssessmentDetailsArray(AssessmentType $assessmentType): array
    {
        return match ($assessmentType->getName()) {
            FormatEnum::QUIZ->value => self::quizAssessmentToArray($assessmentType),
            default => [],
        };
    }

    protected static function quizAssessmentToArray(QuizAssessment $assessmentType): array
    {
        $questions = [];
        foreach ($assessmentType->getQuestionsAttempts() as $question) {
            $questions[] = [
                'content' => $question->getQuestion()->getContent(),
                'answers' => $question->getQuestion()->getOptions(),
                'correctAnswer' => $question->getQuestion()->getCorrectAnswer(),
                'explanation' => $question->getExplanation(),
                'yourAnswer' => $question->getAnswer(),
                'isCorrect' => $question->isCorrect(),
                'takenTime' => $question->getTakenTime(),
            ];
        }

        return [
            'assessmentTypeId' => $assessmentType->getId(),
            'assessmentTypeName' => $assessmentType->getName(),
            'answeredQuestions' => $assessmentType->getQuestionCount(),
            'correctAnswers' => $assessmentType->getCorrectAnswerCount(),
            'duration' => $assessmentType->getDuration(),
            'questions' => $questions,
        ];
    }
}
