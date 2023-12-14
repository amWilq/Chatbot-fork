<?php

namespace App\Infrastructure\Events;

use Swoole\WebSocket\Server;
use Symfony\Contracts\EventDispatcher\Event;

class WebsocketPipeMessageEvent extends Event
{
    public function __construct(
        public readonly Server $server,
        public readonly int $fd
    ) {
    }
}
