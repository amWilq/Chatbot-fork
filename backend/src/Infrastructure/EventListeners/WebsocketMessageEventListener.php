<?php

namespace App\Infrastructure\EventListeners;

use App\Application\Services\AssessmentService;
use App\Infrastructure\Events\WebsocketMessageEvent;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[AsEventListener(event: WebsocketMessageEvent::class, method: 'onWebsocketMessage')]
class WebsocketMessageEventListener
{
    private readonly ConsoleLogger $consoleLogger;
    private readonly ConsoleOutput $output;

    public function __construct(
        private readonly AssessmentService $assessmentService,
        private readonly JsonEncoder $jsonEncoder,
    ) {
        $this->output = new ConsoleOutput(OutputInterface::VERBOSITY_DEBUG);
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

    public function onWebsocketMessage(WebsocketMessageEvent $event): void
    {
        try {
            $this->assessmentService->interactAssessment(
                $this->jsonEncoder->decode(
                    $event->frame->data, JsonEncoder::FORMAT, ['json_decode_associative' => false]
                )
            );
        } catch (\Exception $e) {
            $this->consoleLogger->error($e->getMessage());
            $this->consoleLogger->log(LogLevel::INFO, 'Performing save close!');
            $event->server->disconnect(
                $event->frame->fd,
                SWOOLE_WEBSOCKET_CLOSE_DATA_ERROR,
                'Something went wrong, all states has been saved!'
            );
        }
    }
}
