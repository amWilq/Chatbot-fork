<?php

namespace App\Infrastructure\EventListeners;

use App\Infrastructure\Events\WebsocketCloseEvent;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: WebsocketCloseEvent::class, method: 'onWebsocketClose')]
class WebsocketCloseEventListener
{
    private ConsoleLogger $consoleLogger;
    private ConsoleOutput $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput(OutputInterface::VERBOSITY_DEBUG);
        $this->consoleLogger = new ConsoleLogger($this->output);
    }

    public function onWebsocketClose(WebsocketCloseEvent $event): void
    {
        if ($event->server->isEstablished($event->fd)) {
            $event->server->send($event->fd, 'Clean close, see you later!');
            $this->consoleLogger->log(LogLevel::INFO, 'Closed clean, see ya later!');
        } else {
            $this->consoleLogger->log(LogLevel::ERROR, 'Something went wrong! Server closed unexpectedly!');
        }
    }
}
