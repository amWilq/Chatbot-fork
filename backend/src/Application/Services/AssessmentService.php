<?php

namespace App\Application\Services;

use App\Application\Dtos\AssessmentStartDTO;
use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use App\Domain\Category\Entities\Category;
use App\Domain\Language\Entities\Language;
use App\Domain\User\Entities\User;
use App\Infrastructure\Persistence\Repository\AssessmentEntityRepository;
use App\Infrastructure\Persistence\Repository\AssessmentTypeEntityRepository;
use App\Infrastructure\Persistence\Repository\CategoryEntityRepository;
use App\Infrastructure\Persistence\Repository\LanguageEntityRepository;
use App\Infrastructure\Persistence\Repository\UserEntityRepository;
use JsonSchema\Exception\JsonDecodingException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AssessmentService
{
    private const ASSESSMENT_START_SCHEMA = 'AssessmentStartRequest';

    private Assessment $assessment;

    public function getAssessment(): Assessment
    {
        return $this->assessment;
    }

    public function setAssessment(Assessment $assessment): void
    {
        $this->assessment = $assessment;
    }

    public function __construct(
        private readonly UserEntityRepository $userEntityRepository,
        private readonly AssessmentEntityRepository $assessmentEntityRepository,
        private readonly AssessmentTypeEntityRepository $assessmentTypeEntityRepository,
        private readonly CategoryEntityRepository $categoryEntityRepository,
        private readonly LanguageEntityRepository $languageEntityRepository,
        private readonly SchemaValidatorService $schemaValidatorService,
    ) {
    }

    /**
     * @throws BadRequestException|JsonDecodingException|
     */
    public function startAssessment(object $postData, string $assessmentTypeName): array
    {
        $this->schemaValidatorService->validateRequestSchema($postData, self::ASSESSMENT_START_SCHEMA);

        [
            $user,
            $category,
            $language,
            $assessmentType,
        ] = $this->manageAssociations($postData, $assessmentTypeName);

        $this->setAssessment(
            Assessment::create(
                user: $user,
                category: $category,
                language: $language,
                assessmentType: $assessmentType,
                difficulty: $postData->difficulty,
                startTime: $postData->startTime,
            )
        );

        $this->assessmentEntityRepository->save($this->getAssessment());

        return AssessmentStartDTO::fromDomainEntity($this->getAssessment())->toArray();
    }

    protected function manageAssociations(object $postData, string $assessmentTypeName): array
    {
        $user = $this->getUser($postData->userDeviceId, $postData->username);
        $category = $this->getCategory($postData->categoryId);
        $language = $this->getLanguage($postData->languageId);
        $assessmentType = $this->getAssessmentType($postData, $assessmentTypeName);

        return [$user, $category, $language, $assessmentType];
    }

    /**
     * @throws BadRequestException
     */
    protected function getUser(string $userDeviceId, string $username = null): User
    {
        $user = $this->userEntityRepository->findOneBy(['user_device_id' => $userDeviceId]);
        if (!$user) {
            $user = User::create(
                name: $username ?? 'default_name',
                deviceId: $userDeviceId
            );
            $this->userEntityRepository->save($user);
        }

        return $user;
    }

    /**
     * @throws BadRequestException
     */
    protected function getCategory(string $categoryId): Category
    {
        $category = $this->categoryEntityRepository->find($categoryId);
        if (!$category) {
            throw new BadRequestException('Chosen Category does not exist.');
        }

        return $category;
    }

    /**
     * @throws BadRequestException
     */
    protected function getLanguage(string $languageId): Language
    {
        $language = $this->languageEntityRepository->find($languageId);
        if (!$language) {
            throw new BadRequestException('Chosen Language does not exist.');
        }

        return $language;
    }

    /**
     * @throws BadRequestException
     */
    protected function getAssessmentType(object $postData, string $assessmentTypeName): AssessmentType
    {
        $assessmentType = $this->assessmentTypeEntityRepository->find($postData->assessmentTypeId);
        if (!$assessmentType) {
            throw new BadRequestException('Chosen Assessment Type does not exist.');
        }

        if ($assessmentType->getName() !== $assessmentTypeName) {
            throw new BadRequestException("Given assessment type name doesn't match the assessment type id in payload.");
        }

        return $this->retrieveAssessmentType($postData, $assessmentTypeName);
    }

    protected function retrieveAssessmentType(object $postData, string $assessmentTypeName): AssessmentType
    {
        return match ($assessmentTypeName) {
            'quiz' => QuizAssessment::create(
                durationInSeconds: $postData->duration ?? '10',
            ),
            default => throw new BadRequestException('Selected AssessmentType not supported on server.'),
        };
    }
}
