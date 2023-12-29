<?php

namespace App\Application\Services;

use App\Application\Dtos\AssessmentDTO;
use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Enums\FormatEnum;
use App\Domain\Assessment\Repositories\AssessmentRepositoryInterface;
use App\Domain\Assessment\Types\FreeTextAssessment\FreeTextAssessment;
use App\Domain\Assessment\Types\QuizAssessment\QuestionAttempt;
use App\Domain\Assessment\Types\QuizAssessment\QuizAssessment;
use App\Domain\Assessment\Types\CodeSnippetAssessment\CodeSnippetAttempt;
use App\Domain\Assessment\Types\CodeSnippetAssessment\CodeSnippetAssessment;
use App\Domain\Assessment\ValueObjects\Message;
use App\Domain\Assessment\ValueObjects\Question;
use App\Domain\Assessment\ValueObjects\CodeSnippet;
use App\Infrastructure\OpenAI\ApiClientInterface;
use App\Shared\Enums\AssistantPromptsEnum;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Contracts\Cache\CacheInterface;

class OpenAIService implements OpenAIServiceInterface
{
    private const ASSISTANT_NAME = 'chatbot_assessment';
    private const THREAD_NAME_TEMPLATE = 'trd_%s_%s';

    private const USER_SENDER = 'user';
    private const MODEL_SENDER = 'model';
    private static array $assistant;

    private array $thread;

    public function __construct(
        private readonly ApiClientInterface $apiClient,
        private readonly CacheInterface $cache,
        private readonly AssessmentRepositoryInterface $assessmentRepository,
    ) {
        $this->getAssistant();
    }

    public function generateProblem(Assessment $assessment, object $data): array
    {
        $thread = $this->getThread($data);

        switch ($assessment->getAssessmentType()->getName()) {
            case FormatEnum::QUIZ->value:
                $jsonSchema = AssistantPromptsEnum::QUIZ_PROBLEM_SCHEMA->value;
                $this->generateQuizQuestion($assessment, $thread['id'], $jsonSchema);
                break;
            case FormatEnum::CODE_SNIPPET->value:
                $jsonSchema = AssistantPromptsEnum::CODE_SNIPPET_PROBLEM_SCHEMA->value;
                $this->generateCodeSnippetProblem($assessment, $thread['id'], $jsonSchema);
                break;
            case FormatEnum::FREE_TEXT->value:
                $jsonSchema = AssistantPromptsEnum::FREE_TEXT_SCHEMA->value;
                $this->generateModelFreeText($assessment, $thread['id'], $jsonSchema);
            default:
                break;
        }

        return $this->apiClient->getMessages($thread['id']);
    }

    public function handleAnswer(Assessment $assessment, object $data): array
    {
        $thread = $this->getThread($data);

        $jsonSchema = AssistantPromptsEnum::ANSWER_SCHEMA->value;

        match ($assessment->getAssessmentType()->getName()) {
            FormatEnum::QUIZ->value => $this->handleQuizAnswer($assessment, $thread['id'], $jsonSchema, $data),
            FormatEnum::CODE_SNIPPET->value => $this->handleCodeSnippetAnswer($assessment, $thread['id'], $jsonSchema, $data),
            FormatEnum::FREE_TEXT->value => $this->handleUserFreeText($assessment, $thread['id'], AssistantPromptsEnum::FREE_TEXT_SCHEMA->value, $data),
        };

        return $this->apiClient->getMessages($thread['id']);
    }

    public function getGeneratedFeedback(Assessment $assessment): array
    {
        $thread = $this->getThread((object) [
            'assessmentTypeName' => $assessment->getAssessmentType()->getName(),
            'assessmentId' => $assessment->getId()->toString(),
        ]);

        $message = sprintf(
            AssistantPromptsEnum::GENERATE_FEEDBACK_PROMPT->value,
            $assessment->getId()->toString(),
        );
        $this->apiClient->addMessage($thread['id'], $message);

        $feedback = $this->run($thread['id']);

        if (isset($difficulty['status'])) {
            return [];
        }
        $feedback = end($feedback);

        $assessment->setFeedback($feedback->feedback);

        return $this->apiClient->getMessages($thread['id']);
    }

    protected function getAssistant(): array
    {
        $item = $this->cache->getItem(self::ASSISTANT_NAME);

        if (!$item->isHit()) {
            self::$assistant = $this->apiClient->createAssistant(
                self::ASSISTANT_NAME,
                AssistantPromptsEnum::ASSISTANT_INSTRUCTIONS->value
            );
            $item->set(self::$assistant);
            $this->cache->save($item);
        }

        self::$assistant = $item->get();

        return self::$assistant;
    }

    protected function getThread(object $data): array
    {
        $item = $this->cache->getItem(
            sprintf(self::THREAD_NAME_TEMPLATE, $data->assessmentTypeName, $data->assessmentId)
        );

        if (!$item->isHit()) {
            $this->thread = $this->apiClient->createThread();
            $item->set($this->thread);
            $this->cache->save($item);
        }

        $this->thread = $item->get();

        return $this->thread;
    }

    protected function run(string $threadId): array
    {
        $run = $this->apiClient->runAssistant($threadId, self::$assistant['id']);
        $status = $this->apiClient->getRunStatus($threadId, $run['id']);

        $generatedOutput = [];

        if (isset($status['status'])) {
            do {
                if ('requires_action' === $status['status']) {
                    $toolOutputs = [];
                    foreach ($status['required_action']['submit_tool_outputs'] as $toolCalls) {
                        foreach ($toolCalls as ['id' => $id, 'type' => $type, 'function' => $function]) {
                            $output = $this->{$function['name']}($function['arguments'], $generatedOutput);
                            $toolOutputs[] = [
                                'tool_call_id' => $id,
                                'output' => json_encode($output),
                            ];
                        }
                    }
                    $this->apiClient->submitToolOutputs($threadId, $run['id'], $toolOutputs);
                }
                $status = $this->apiClient->getRunStatus($threadId, $run['id']);
            } while ('completed' !== $status['status']);
        }

        return !empty($generatedOutput) ? $generatedOutput : $status;
    }

    protected function generateQuizQuestion(Assessment $assessment, string $threadId, string $jsonSchema): void
    {
        $message = sprintf(
            AssistantPromptsEnum::GENERATE_QUESTION_PROMPT->value,
            $assessment->getAssessmentType()->getName(),
            $assessment->getLanguage()->getName(),
            $assessment->getCurrentDifficulty()?->value,
            $jsonSchema
        );
        $this->apiClient->addMessage($threadId, $message);

        $questions = $this->run($threadId);

        if (isset($questions['status'])) {
            return;
        }
        $question = end($questions);

        $q = Question::create(
            content: $question->content,
            options: $question->options,
            correctAnswer: $question->correctAnswer
        );
        $qa = QuestionAttempt::create(
            question: $q,
        );

        /** @var QuizAssessment $quiz */
        $quiz = $assessment->getAssessmentType();
        $quiz->setQuestionsAttempts($qa);
    }

    protected function handleQuizAnswer(Assessment $assessment, string $threadId, string $jsonSchema, object $data): void
    {
        $message = sprintf(
            AssistantPromptsEnum::HANDLE_ANSWER_PROMPT->value,
            $data->data->answer,
            $jsonSchema
        );
        $this->apiClient->addMessage($threadId, $message);

        $answer = $this->run($threadId);

        if (isset($answer['status'])) {
            return;
        }
        $answer = end($answer);

        /** @var QuizAssessment $quiz */
        $quiz = $assessment->getAssessmentType();
        $questionAttempts = $quiz->getQuestionsAttempts();

        /** @var QuestionAttempt $currentAttempt */
        $currentAttempt = end($questionAttempts);

        $currentAttempt->setAnswer($data->data->answer, (int) $data->data->takenTime);
        $currentAttempt->setExplanation($answer->explanation);
        $currentAttempt->checkAnswerCorrectness();

        $quiz->checkAndIncreaseCorrectAnswers($currentAttempt);
        $this->getAdjustedDifficulty($assessment, $threadId);
    }

    protected function generateCodeSnippetProblem(Assessment $assessment, string $threadId, string $jsonSchema): void
    {
        $message = sprintf(
            AssistantPromptsEnum::GENERATE_QUESTION_PROMPT->value,
            $assessment->getAssessmentType()->getName(),
            $assessment->getLanguage()->getName(),
            $assessment->getCurrentDifficulty()?->value,
            $jsonSchema
        );
        $this->apiClient->addMessage($threadId, $message);

        $snippets = $this->run($threadId);

        if (isset($snippets['status'])) {
            return;
        }
        $snippet = end($snippets);

        $s = CodeSnippet::create(
            code: $snippet->content,
            correctSolution: $snippet->correctAnswer
        );

        $sa = CodeSnippetAttempt::create(
            codeSnippet: $s
        );

        /** @var CodeSnippetAssessment $codeSnippet */
        $codeSnippet = $assessment->getAssessmentType();
        $codeSnippet->setSnippetAttempts($sa);
    }

    protected function handleCodeSnippetAnswer(Assessment $assessment, string $threadId, string $jsonSchema, object $data): void
    {
        $message = sprintf(
            AssistantPromptsEnum::HANDLE_ANSWER_PROMPT->value,
            $data->data->answer,
            $jsonSchema
        );
        $this->apiClient->addMessage($threadId, $message);

        $answer = $this->run($threadId);

        if (isset($answer['status'])) {
            return;
        }
        $answer = end($answer);

        /** @var CodeSnippetAssessment $snippet */
        $snippet = $assessment->getAssessmentType();
        $snippetAttempts = $snippet->getSnippetAttempts();

        /** @var CodeSnippetAttempt $currentAttempt */
        $currentAttempt = end($snippetAttempts);

        $currentAttempt->setAttempt($data->data->answer, (int) $data->data->takenTime);
        $currentAttempt->setExplanation($answer->explanation);
        $currentAttempt->setCorrectness($answer->isCorrect);

        $snippet->checkAndIncreaseCorrectSnippets($currentAttempt);
        $this->getAdjustedDifficulty($assessment, $threadId);
    }

    protected function generateModelFreeText(Assessment $assessment, string $threadId, string $jsonSchema): void
    {
        $message = sprintf(
            AssistantPromptsEnum::GENERATE_FREE_TEXT_INTERACTION->value,
            $assessment->getLanguage()->getName(),
            $assessment->getCurrentDifficulty()?->value,
            $jsonSchema
        );

        $this->apiClient->addMessage($threadId, $message);

        $freeTextInteractions = $this->run($threadId);

        if (isset($freeTextInteractions['status'])) {
            return;
        }
        $interaction = end($freeTextInteractions);

        $m = Message::create(
            sender: self::MODEL_SENDER,
            message: $interaction->message
        );
        /** @var FreeTextAssessment $freeText */
        $freeText = $assessment->getAssessmentType();
        $freeText->addMessage($m);
    }

    protected function handleUserFreeText(Assessment $assessment, string $threadId, string $jsonSchema, object $data): void
    {
        $message = sprintf(
            AssistantPromptsEnum::HANDLE_ANSWER_PROMPT->value,
            $data->data->message,
            $jsonSchema
        );
        $this->apiClient->addMessage($threadId, $message);

        $answer = $this->run($threadId);

        if (isset($answer['status'])) {
            return;
        }
        $answer = end($answer);

        $m = Message::create(
            sender: self::USER_SENDER,
            message: $answer->userAnswer
        );
        /** @var FreeTextAssessment $freeText */
        $freeText = $assessment->getAssessmentType();
        $freeText->addMessage($m);

        $this->getAdjustedDifficulty($assessment, $threadId);
    }

    protected function getAdjustedDifficulty(Assessment $assessment, string $threadId): void
    {
        $message = sprintf(
            AssistantPromptsEnum::ADJUST_DIFFICULTY_PROMPT->value,
            $assessment->getAssessmentType()->getName(),
            $assessment->getCategory()->getName(),
            $assessment->getLanguage()->getName(),
        );
        $this->apiClient->addMessage($threadId, $message);

        $difficulty = $this->run($threadId);

        if (isset($difficulty['status'])) {
            return;
        }
        $difficulty = end($difficulty);

        $assessment->setCurrentDifficulty($difficulty->adjustedDifficulty);
    }

    protected function generateQuestions(string $jsonString, array &$generatedOutput): bool
    {
        $object = json_decode($jsonString);
        $generatedOutput[] = $object;

        return true;
    }

    protected function handleUserInput(string $jsonString, array &$generatedOutput): bool
    {
        $object = json_decode($jsonString);
        $generatedOutput[] = $object;

        return true;
    }

    protected function adjustDifficulty(string $jsonString, array &$generatedOutput): bool
    {
        $object = json_decode($jsonString);
        $generatedOutput[] = $object;

        return true;
    }

    protected function feedback(string $jsonString, array &$generatedOutput): bool
    {
        $object = json_decode($jsonString);
        $generatedOutput[] = $object;

        return true;
    }

    protected function retrieveAssessment(string $assessmentId): array
    {
        $cacheItem = $this->cache->getItem('assessment_'.json_decode($assessmentId, false)->assessmentId);
        if ($cacheItem->isHit()) {
            return AssessmentDTO::fromDomainEntity($cacheItem->get())->toArray();
        }
        $assessment = $this->assessmentRepository->find($assessmentId);
        if (!$assessment) {
            throw new BadRequestException('Assessment was not found.');
        }

        return AssessmentDTO::fromDomainEntity($assessment)->toArray();
    }

    protected function generateFreeTextInteraction(string $jsonString, array &$generatedOutput): bool
    {
        $object = json_decode($jsonString);
        $generatedOutput[] = $object;

        return true;
    }
}
