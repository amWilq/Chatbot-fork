<?php

namespace App\Infrastructure\Events;

use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Symfony\Contracts\EventDispatcher\Event;

class WebsocketMessageEvent extends Event
{
    public function __construct(
        public readonly Server $server,
        public readonly Frame $frame
    ) {
    }
}
