<?php

namespace App\Infrastructure\EventListeners;

use App\Application\Services\WebsocketService;
use App\Infrastructure\Events\WebsocketOpenEvent;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: WebsocketOpenEvent::class, method: 'onWebsocketOpen')]
class WebsocketOpenEventListener
{
    private ConsoleLogger $consoleLogger;
    private ConsoleOutput $output;
    public function __construct(
        private WebsocketService $websocketService,
    ) {
        $this->output = new ConsoleOutput();
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

    public function onWebsocketOpen(WebsocketOpenEvent $event)
    {
    }
}
