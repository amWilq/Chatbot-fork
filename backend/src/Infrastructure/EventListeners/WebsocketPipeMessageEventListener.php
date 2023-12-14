<?php

namespace App\Infrastructure\EventListeners;

use App\Infrastructure\Events\WebsocketPipeMessageEvent;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: WebsocketPipeMessageEvent::class, method: 'onWebsocketPipeMessage')]
class WebsocketPipeMessageEventListener
{
    private ConsoleLogger $consoleLogger;
    private ConsoleOutput $output;

    public function __construct(
    ) {
        $this->output = new ConsoleOutput(OutputInterface::VERBOSITY_DEBUG);
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

    public function onWebsocketPipeMessage(WebsocketPipeMessageEvent $event)
    {
        $this->consoleLogger->log(LogLevel::DEBUG, $event->fd);
    }
}
