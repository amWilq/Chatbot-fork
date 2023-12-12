<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Types\QuizAssessment\QuestionAttempt;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use App\Domain\Assessment\ValueObjects\Question;
use App\Infrastructure\Persistence\Entities\AssessmentDetailsEntity;
use App\Infrastructure\Persistence\Entities\AssessmentEntity;
use App\Infrastructure\Persistence\Entities\PersistenceEntityInterface;
use App\Shared\Models\AggregateRoot;
use Doctrine\Persistence\ManagerRegistry;

class AssessmentEntityRepository extends BaseEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly UserEntityRepository $userEntityRepository,
        private readonly LanguageEntityRepository $languageEntityRepository,
        private readonly CategoryEntityRepository $categoryEntityRepository,
    ) {
        parent::__construct($registry, AssessmentEntity::class);
    }

    public function find(
        $id,
        $lockMode = null,
        $lockVersion = null,
        bool $raw = false,
    ): null|Assessment|AssessmentEntity {
        $entity = parent::find(
            $id,
            $lockMode,
            $lockVersion
        );

        if ($entity instanceof AssessmentEntity && $raw) {
            return $entity;
        }

        return $entity instanceof AssessmentEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    public function findOneBy(
        array $criteria,
        array $orderBy = null,
        bool $raw = false,
    ): null|Assessment|AssessmentEntity {
        $entity = parent::findOneBy(
            $criteria,
            $orderBy
        );

        if ($entity instanceof AssessmentEntity && $raw) {
            return $entity;
        }

        return $entity instanceof AssessmentEntity ? $this->mapToDomainEntity(
            $entity
        ) : null;
    }

    /**
     * @return Assessment[]|AssessmentEntity[]
     */
    public function findAll(bool $raw = false): array
    {
        return $this->findBy([], raw: $raw);
    }

    /**
     * @return Assessment[]|AssessmentEntity[]
     */
    public function findBy(
        array $criteria,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        bool $raw = false
    ): array {
        $entities = parent::findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );

        return !$raw ? array_map([$this, 'mapToDomainEntity'], $entities) : $entities;
    }

    public function save(Assessment|AggregateRoot $aggregateRoot): void
    {
        $this->getEntityManager()->persist(
            AssessmentEntity::fromDomainEntity($aggregateRoot, $this->getEntityManager())
        );
        $this->getEntityManager()->flush();
    }

    public function delete(Assessment|AggregateRoot $aggregateRoot): void
    {
        $this->getEntityManager()->remove(
            AssessmentEntity::fromDomainEntity($aggregateRoot, $this->getEntityManager())
        );
        $this->getEntityManager()->flush();
    }

    public function update(Assessment|AggregateRoot $aggregateRoot): void
    {
        $assessment = AssessmentEntity::fromDomainEntity($aggregateRoot, $this->getEntityManager());
        $this->getEntityManager()->contains($assessment) ?
            $this->getEntityManager()->flush() :
            $this->save($aggregateRoot);
    }

    protected function mapToDomainEntity(
        AssessmentEntity|PersistenceEntityInterface $entity
    ): Assessment {
        return Assessment::create(
            id: $entity->getId(),
            status: $entity->getStatus(),
            user: $this->userEntityRepository->mapToDomainEntity(
                $entity->getUser()
            ),
            category: $this->categoryEntityRepository->mapToDomainEntity(
                $entity->getCategory()
            ),
            language: $this->languageEntityRepository->mapToDomainEntity(
                $entity->getLanguage()
            ),
            startTime: $entity->getStartTime(),
            endTime: $entity->getEndTime(),
            difficulty: $entity->getStartDifficulty(),
            currentDifficulty: $entity->getCurrentDifficulty(),
            difficultyAtEnd: $entity->getEndDifficulty(),
            feedback: $entity->getFeedback(),
            assessmentType: $this->assessmentDetailsToDomainObject(
                $entity->getAssessmentDetails()
            ),
        );
    }

    private function assessmentDetailsToDomainObject(
        AssessmentDetailsEntity $assessmentDetails
    ): ?AssessmentType {
        $assessmentType = $assessmentDetails->getAssessmentType();
        switch ($assessmentType->getName()) {
            case 'quiz':
                if (empty($assessmentDetails->getAssessmentDetails())) {
                    return QuizAssessment::create(
                        id: $assessmentDetails->getAssessmentType()->getId(),
                        durationInSeconds: 15
                    );
                }

                [
                    'answeredQuestions' => $questionCount,
                    'correctAnswers' => $correctAnswerCount,
                    'duration' => $durationInSeconds,
                    'questions' => $questions,
                ] = $assessmentDetails->getAssessmentDetails();
                $attempts = [];
                foreach ($questions as $question) {
                    $q = Question::create(
                        content: $question['content'],
                        options: $question['options'],
                        correctAnswer: $question['correctAnswer'],
                        explanation: $question['explanation'],
                    );
                    $attempts[] = QuestionAttempt::create(
                        userAnswer: $question['userAnswer'],
                        takenTime: $question['takenTime'],
                        isCorrect: $question['isCorrect'],
                        question: $q
                    );
                }

                return QuizAssessment::create(
                    id: $assessmentDetails->getAssessmentType()->getId(),
                    questionCount: $questionCount,
                    correctAnswerCount: $correctAnswerCount,
                    questionsAttempts: $attempts,
                    durationInSeconds: $durationInSeconds,
                );
            default:
                return null;
        }
    }
}
