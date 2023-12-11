<?php

namespace App\Infrastructure\EventListeners;

use App\Infrastructure\Events\WebsocketMessageEvent;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: WebsocketMessageEvent::class, method: 'onWebsocketMessage')]
class WebsocketMessageEventListener
{
    public function onWebsocketMessage(WebsocketMessageEvent $event)
    {
        $output = new ConsoleOutput();
        $output->writeln($event->getData());
    }

}
