<?php

namespace App\Infrastructure\EventListeners;

use App\Domain\Assessment\Entities\Assessment;
use App\Domain\Assessment\Enums\AssessmentStatusEnum;
use App\Infrastructure\Events\WebsocketOpenEvent;
use App\Infrastructure\Persistence\Repository\AssessmentEntityRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LogLevel;
use Swoole\Client\Exception;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\Cache\CacheInterface;

#[AsEventListener(event: WebsocketOpenEvent::class, method: 'onWebsocketOpen')]
class WebsocketOpenEventListener
{
    private ConsoleLogger $consoleLogger;
    private ConsoleOutput $output;

    /**
     * @param MemcachedAdapter $cache
     */
    public function __construct(
        private readonly AssessmentEntityRepository $assessmentEntityRepository,
        private readonly CacheInterface $cache,
    ) {
        $this->output = new ConsoleOutput(OutputInterface::VERBOSITY_DEBUG);
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

    /**
     * @throws InvalidArgumentException|Exception
     */
    public function onWebsocketOpen(WebsocketOpenEvent $event): void
    {
        $queryParams = $event->request->get;
        $this->validateQueryParams($event, $queryParams);

        $cacheItem = $this->cache->getItem('assessment_'.$queryParams['assessmentId']);
        if (!$cacheItem->isHit()) {
            $assessment = $this->assessmentEntityRepository->find($queryParams['assessmentId']);
        } else {
            $assessment = $cacheItem->get();
        }

        if (is_null($assessment)) {
            $event->server->push($event->request->fd, 'No assessment found with id: '.$queryParams['assessmentId']);
            $event->server->close($event->request->fd);

            return;
        }

        /** @var Assessment $assessment */
        if (AssessmentStatusEnum::ASSESSMENT_COMPLETE_SUCCESS === $assessment->getStatus()) {
            $event->server->push($event->request->fd, 'Assessment with id: '.$queryParams['assessmentId'].' is already completed.');
            $event->server->close($event->request->fd);

            return;
        }

        $this->consoleLogger->log(LogLevel::INFO, 'Success Open');
    }

    /**
     * @throws Exception
     */
    protected function validateQueryParams(WebsocketOpenEvent $event, array $queryParams): bool
    {
        if (!isset($queryParams['assessmentId'])) {
            $event->server->exists($event->request->fd) ?
                $this->consoleLogger->log(LogLevel::DEBUG, 'Success exists') :
                throw new Exception("Frame doesn't exist or wrong id provided.");
            $event->server->push($event->request->fd, "Query param: 'assessmentId' is required");

            $event->server->close($event->request->fd) ?
                $this->consoleLogger->log(LogLevel::DEBUG, 'Success close') :
                throw new Exception('Error while closing connection.');

            return false;
        }

        return true;
    }
}
