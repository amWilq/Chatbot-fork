<?php

namespace App\Application\Services;

use App\Application\Dtos\AssessmentDTO;
use App\Application\Dtos\AssessmentStartDTO;
use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\AssessmentStatusEnum;
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
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AssessmentService
{
    private const ASSESSMENT_START_SCHEMA = 'AssessmentStartRequest';
    private const ASSESSMENT_COMPLETE_SCHEMA = 'AssessmentCompleteRequest';

    private Assessment $assessment;
    private ConsoleOutput $output;
    private ConsoleLogger $consoleLogger;

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
        $this->output = new ConsoleOutput();
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

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
                startTime: new \DateTime($postData->startTime),
            )
        );
        $dto = AssessmentStartDTO::fromDomainEntity($this->assessment)->toArray();
        $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_IN_PROGRESS);

        $this->assessmentEntityRepository->save($this->assessment);

        return $dto;
    }

    public function completeAssessment(object $postData, array $pathParams): array
    {
        $this->schemaValidatorService->validateRequestSchema($postData, self::ASSESSMENT_COMPLETE_SCHEMA);
        [$assessmentTypeName, $assessmentId] = $pathParams;

        $this->setAssessment(
            $this->getAssessmentById($assessmentId)
        );

        $this->isRequestedUserAssociated($this->assessment, $postData, AssessmentStatusEnum::ASSESSMENT_COMPLETE_ERROR);
        $this->isRequestedAssessmentTypeAssociated($this->assessment, $postData, $assessmentTypeName, AssessmentStatusEnum::ASSESSMENT_COMPLETE_ERROR);

        $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_COMPLETE_SUCCESS);
        $this->assessmentEntityRepository->update($this->assessment);

        return AssessmentDTO::fromDomainEntity($this->assessment)->toArray();
    }

    protected function manageAssociations(object $postData, string $assessmentTypeName): array
    {
        $user = $this->getUser($postData->userDeviceId, $postData->username);
        $category = $this->getCategory($postData->categoryId);
        $language = $this->getLanguage($postData->languageId);
        $assessmentType = $this->getAssessmentType($postData, $assessmentTypeName);

        return [$user, $category, $language, $assessmentType];
    }

    protected function getUser(string $userDeviceId, string $username = null): User
    {
        $user = $this->userEntityRepository->findOneBy(['deviceId' => $userDeviceId]);
        if (is_null($user)) {
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
    protected function isRequestedUserAssociated(Assessment $assessment, object $postData, AssessmentStatusEnum $errorStatus): bool
    {
        if (!$assessment->getUser()->equals(
            $this->getUser($postData->userDeviceId)
        )) {
            $assessment->setStatus($errorStatus);
            throw new BadRequestException('This user is not associated with this assessment.');
        }

        return true;
    }

    /**
     * @throws BadRequestException
     */
    protected function getCategory(string $categoryId): Category
    {
        $category = $this->categoryEntityRepository->find($categoryId);
        if (is_null($category)) {
            $this->consoleLogger->error(print_r($category, true));
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
        if (is_null($language)) {
            $this->consoleLogger->error(print_r($language, true));
            throw new BadRequestException('Chosen Language does not exist.');
        }

        return $language;
    }

    /**
     * @throws BadRequestException
     */
    protected function getAssessmentById(string $assessmentId): Assessment
    {
        $assessment = $this->assessmentEntityRepository->find($assessmentId);
        if (!$assessment) {
            $this->consoleLogger->error(print_r($assessment, true));
            throw new BadRequestException('Assessment was not found.');
        }

        return $assessment;
    }

    /**
     * @throws BadRequestException
     */
    protected function getAssessmentType(object $postData, string $assessmentTypeName): AssessmentType
    {

        $assessmentType = $this->assessmentTypeEntityRepository->findOneBy(['name' => $assessmentTypeName]);
        if (is_null($assessmentType)) {
            $this->consoleLogger->error((string) print_r($assessmentType, true));
            throw new BadRequestException('Chosen Assessment Type does not exist.');
        }

        if ($assessmentType->getName() !== $assessmentTypeName) {
            $this->consoleLogger->error(print_r([$assessmentType->getName(), $assessmentTypeName], true));
            throw new BadRequestException("Given assessment type name doesn't match the assessment type id in payload.");
        }

        return $this->retrieveAssessmentType($postData, $assessmentType);
    }

    /**
     * @throws BadRequestException
     */
    protected function isRequestedAssessmentTypeAssociated(Assessment $assessment, object $postData, string $name, AssessmentStatusEnum $errorStatus): bool
    {
        if (!$assessment->getAssessmentType()->equals(
            $this->getAssessmentType($postData, $name)
        )) {
            $assessment->setStatus($errorStatus);
            throw new BadRequestException('This assessment type is not associated with this assessment.');
        }

        return true;
    }

    /**
     * @throws BadRequestException
     */
    protected function retrieveAssessmentType(object $postData, AssessmentType $assessmentType): AssessmentType
    {
        return match ($assessmentType->getName()) {
            'quiz' => QuizAssessment::create(
                id: $assessmentType->getId()->toString(),
                durationInSeconds: $postData->duration ?? '10',
            ),
            default => throw new BadRequestException('Selected AssessmentType not supported on server.'),
        };
    }
}
