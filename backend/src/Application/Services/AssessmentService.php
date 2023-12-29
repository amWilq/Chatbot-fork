<?php

namespace App\Application\Services;

use App\Application\Dtos\AssessmentDTO;
use App\Application\Dtos\AssessmentStartDTO;
use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Entities\AssessmentType;
use App\Domain\Assessment\Enums\AssessmentStatusEnum;
use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\Repositories\AssessmentRepositoryInterface;
use App\Domain\Assessment\Repositories\AssessmentTypeRepositoryInterface;
use App\Domain\Assessment\Types\CodeSnippetAssessment\CodeSnippetAssessment;
use App\Domain\Assessment\Types\CodeSnippetAssessment\CodeSnippetAttempt;
use App\Domain\Assessment\Types\FreeTextAssessment\FreeTextAssessment;
use App\Domain\Assessment\Types\QuizAssessment\QuestionAttempt;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use App\Domain\Assessment\ValueObjects\Message;
use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Domain\Language\Entities\Language;
use App\Domain\Language\Repositories\LanguageRepositoryInterface;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Shared\Enums\ApiOperationsEnum;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Contracts\Cache\CacheInterface;

class AssessmentService implements AssessmentServiceInterface
{
    private const ASSESSMENT_START_SCHEMA = 'AssessmentStartRequest';
    private const ASSESSMENT_COMPLETE_SCHEMA = 'AssessmentCompleteRequest';
    private const ASSESSMENT_INTERACTION_SCHEMA = 'AssessmentInteractionRequest';
    private const CACHE_NAME_PREFIX = 'assessment_';
    private const THREAD_NAME_TEMPLATE = 'trd_%s_%s';

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

    /**
     * @param MemcachedAdapter $cache
     */
    public function __construct(
        private readonly UserRepositoryInterface $userEntityRepository,
        private readonly AssessmentRepositoryInterface $assessmentEntityRepository,
        private readonly AssessmentTypeRepositoryInterface $assessmentTypeEntityRepository,
        private readonly CategoryRepositoryInterface $categoryEntityRepository,
        private readonly LanguageRepositoryInterface $languageEntityRepository,
        private readonly SchemaValidatorServiceInterface $schemaValidatorService,
        private readonly OpenAIServiceInterface $openAIService,
        private readonly CacheInterface $cache,
    ) {
        $this->output = new ConsoleOutput();
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function startAssessment(object $postData, string $assessmentTypeName): array
    {
        $this->initializeAssessment($postData, $assessmentTypeName);

        $dto = AssessmentStartDTO::fromDomainEntity($this->assessment)->toArray();
        $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_IN_PROGRESS);

        $this->saveAssessment($this->assessment);

        return $dto;
    }

    protected function initializeAssessment(object $postData, string $assessmentTypeName): void
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
                startTime: $postData->startTime ?? null,
            )
        );
    }

    public function interactAssessment(object $data): array
    {
        $this->setAssessment(
            $this->getAssessmentById($data->assessmentId)
        );

        try {
            $this->schemaValidatorService->validateRequestSchema($data, self::ASSESSMENT_INTERACTION_SCHEMA);
        } catch (\Exception $e) {
            $this->consoleLogger->error($e->getMessage());
            $this->saveAssessment($this->assessment);
            throw new \RuntimeException('Unexpected Error!', 5001, $e->getPrevious());
        }

        switch ($data->requestType) {
            case ApiOperationsEnum::USER_INPUT->value:
                $this->handleUserInput($data);
                $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_RECEIVE_USER_INPUT_SUCCESS);
                $output = [
                    'assessmentStatus' => $this->assessment->getStatus()->value,
                    'data' => [],
                ];
                switch ($this->assessment->getAssessmentType()->getName()) {
                    case FormatEnum::QUIZ->value:
                        $array = $this->assessment->getAssessmentType()->getQuestionsAttempts();
                        /** @var QuestionAttempt $questionAttempt */
                        $questionAttempt = end($array);

                        $output['data'] = [
                            'userAnswer' => $questionAttempt->getAnswer(),
                            'isCorrect' => $questionAttempt->isCorrect(),
                            'explanation' => $questionAttempt->getExplanation(),
                        ];
                        break;
                    case FormatEnum::CODE_SNIPPET->value:
                        $array = $this->assessment->getAssessmentType()->getSnippetAttempts();
                        /** @var CodeSnippetAttempt $snippetAttempt */
                        $snippetAttempt = end($array);

                        $output['data'] = [
                            'userAnswer' => $snippetAttempt->getAnswer(),
                            'isCorrect' => $snippetAttempt->isCorrect(),
                            'explanation' => $snippetAttempt->getExplanation(),
                        ];
                        break;
                    case FormatEnum::FREE_TEXT->value:
                        $array = $this->assessment->getAssessmentType()->getMessages();
                        /** @var Message $message */
                        $message = end($array);

                        $output['data'] = [
                            'sender' => $message->getSender(),
                            'message' => $message->getMessage(),
                        ];
                        break;
                }

                $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_IN_PROGRESS);
                $this->saveAssessment($this->assessment);
                return $output;
            case ApiOperationsEnum::GENERATE_OUTPUT->value:
                $this->handleGenerateOutput($data);
                $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_GENERATE_OUTPUT_SUCCESS);
                $output = [
                    'assessmentStatus' => $this->assessment->getStatus()->value,
                    'data' => [],
                ];
                switch ($this->assessment->getAssessmentType()->getName()) {
                    case FormatEnum::QUIZ->value:
                        $array = $this->assessment->getAssessmentType()->getQuestionsAttempts();

                        /** @var QuestionAttempt $questionAttempt */
                        $questionAttempt = end($array);
                        $output['data'] = [
                            'question' => [
                                'content' => $questionAttempt->getQuestion()->getContent(),
                                'options' => $questionAttempt->getQuestion()->getOptions(),
                                'correctAnswer' => $questionAttempt->getQuestion()->getCorrectAnswer(),
                            ],
                        ];
                        break;
                    case FormatEnum::CODE_SNIPPET->value:
                        $array = $this->assessment->getAssessmentType()->getSnippetAttempts();

                        /** @var CodeSnippetAttempt $snippetAttempt */
                        $snippetAttempt = end($array);
                        $output['data'] = [
                            'snippet' => [
                                'code' => $snippetAttempt->getCodeSnippet()->getCode(),
                                'correctSolution' => $snippetAttempt->getCodeSnippet()->getCorrectSolution()
                            ]
                        ];
                        break;
                    case FormatEnum::FREE_TEXT->value:
                        $array = $this->assessment->getAssessmentType()->getMessages();
                        /** @var Message $message */
                        $message = end($array);

                        $output['data'] = [
                            'modelResponse' => [
                                'sender' => $message->getSender(),
                                'message' => $message->getMessage(),
                            ],
                        ];
                        break;
                }
                $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_IN_PROGRESS);
                $this->saveAssessment($this->assessment);

                return $output;
        }

        return [];
    }

    public function completeAssessment(object $postData, array $pathParams): array
    {
        $this->finalizeAssessment($postData, $pathParams);

        return AssessmentDTO::fromDomainEntity($this->assessment)->toArray();
    }

    protected function finalizeAssessment(object $postData, array $pathParams): void
    {
        $this->schemaValidatorService->validateRequestSchema($postData, self::ASSESSMENT_COMPLETE_SCHEMA);
        [$assessmentTypeName, $assessmentId] = $pathParams;

        $this->setAssessment(
            $this->getAssessmentById($assessmentId)
        );

        $this->isRequestedUserAssociated(
            $this->assessment,
            $postData,
            AssessmentStatusEnum::ASSESSMENT_COMPLETE_ERROR
        );
        $this->isRequestedAssessmentTypeAssociated(
            $this->assessment,
            $postData,
            $assessmentTypeName,
            AssessmentStatusEnum::ASSESSMENT_COMPLETE_ERROR
        );

        $this->assessment->setStatus(AssessmentStatusEnum::ASSESSMENT_COMPLETE_SUCCESS);
        $this->assessment->setDifficultyAtEnd();
        $this->assessment->setEndTime(new \DateTime());

        $this->openAIService->getGeneratedFeedback($this->assessment);

        $this->cache->deleteItem(self::CACHE_NAME_PREFIX.$assessmentId);
        $this->cache->deleteItem(
            sprintf(self::THREAD_NAME_TEMPLATE,
                $this->assessment->getAssessmentType()->getName(),
                $this->assessment->getId()->toString()
            )
        );
        $this->assessmentEntityRepository->update($this->assessment);
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
            throw new BadRequestException('Given Category does not exist.');
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
            throw new BadRequestException('Given Language does not exist.');
        }

        return $language;
    }

    /**
     * @throws BadRequestException|InvalidArgumentException
     */
    protected function getAssessmentById(string $assessmentId): Assessment
    {
        $cacheItem = $this->cache->getItem(self::CACHE_NAME_PREFIX.$assessmentId);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        $assessment = $this->assessmentEntityRepository->find($assessmentId);
        if (!$assessment) {
            $this->consoleLogger->error(print_r($assessment, true));
            throw new BadRequestException('Assessment was not found.');
        }

        $this->saveAssessment($assessment);

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
            throw new BadRequestException('Given Assessment Type does not exist.');
        }

        if ($assessmentType->getName() !== $assessmentTypeName) {
            $this->consoleLogger->error(print_r([$assessmentType->getName(), $assessmentTypeName], true));
            throw new BadRequestException("Given assessment type name doesn't match given id: {$postData->assessmentTypeId}.");
        }

        return $this->retrieveAssessmentType($postData, $assessmentType);
    }

    /**
     * @throws BadRequestException
     */
    protected function isRequestedAssessmentTypeAssociated(
        Assessment $assessment,
        object $postData,
        string $name,
        AssessmentStatusEnum $errorStatus
    ): bool {
        if (!$assessment->getAssessmentType()->equals(
            $this->getAssessmentType($postData, $name)
        )) {
            $assessment->setStatus($errorStatus);
            throw new BadRequestException('Given assessment type is not associated with given assessment.');
        }

        return true;
    }

    /**
     * @throws BadRequestException
     */
    protected function retrieveAssessmentType(object $postData, AssessmentType $assessmentType): AssessmentType
    {
        return match ($assessmentType->getName()) {
            FormatEnum::QUIZ->value => QuizAssessment::create(
                id: $assessmentType->getId()->toString(),
                durationInSeconds: $postData->duration ?? '300',
            ),
            FormatEnum::CODE_SNIPPET->value => CodeSnippetAssessment::create(
                id: $assessmentType->getId()->toString()
            ),
            FormatEnum::FREE_TEXT->value => FreeTextAssessment::create(
                id: $assessmentType->getId()->toString()
            ),
            default => throw new BadRequestException('Given AssessmentType not supported on server.'),
        };
    }

    protected function handleUserInput(object $data): array
    {
        return $this->openAIService->handleAnswer($this->assessment, $data);
    }

    protected function handleGenerateOutput(object $data): array
    {
        return $this->openAIService->generateProblem($this->assessment, $data);
    }

    public function saveAssessment(Assessment $assessment): void
    {
        $item = $this->cache->getItem(self::CACHE_NAME_PREFIX.$assessment->getId()->toString());
        $item->set($assessment);

        $this->cache->save($item);
        $this->assessmentEntityRepository->save($assessment);
    }
}
