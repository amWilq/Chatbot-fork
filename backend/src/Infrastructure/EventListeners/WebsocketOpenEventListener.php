<?php

namespace App\Infrastructure\EventListeners;

use App\Application\Services\WebsocketService;
use App\Infrastructure\Events\WebsocketOpenEvent;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: WebsocketOpenEvent::class, method: 'onWebsocketOpen')]
class WebsocketOpenEventListener
{
    public function __construct(
        private WebsocketService $websocketService,
        private ConsoleLogger $consoleLogger,
    ) {
    }

    public function onWebsocketOpen(WebsocketOpenEvent $event)
    {
    }
}
