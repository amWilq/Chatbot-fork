<?php

namespace App\Infrastructure\EventListeners;

use App\Infrastructure\Events\WebsocketCloseEvent;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: WebsocketCloseEvent::class, method: 'onWebsocketClose')]
class WebsocketCloseEventListener
{
    public function onWebsocketClose(WebsocketCloseEvent $event)
    {
        $output = new ConsoleOutput();
        $output->writeln("Test Close");
    }

}
