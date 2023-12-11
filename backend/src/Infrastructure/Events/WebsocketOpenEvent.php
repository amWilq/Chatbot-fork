<?php

namespace App\Infrastructure\Events;

use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use Symfony\Contracts\EventDispatcher\Event;

class WebsocketOpenEvent extends Event
{
    public function __construct(
        public readonly Server $server,
        public readonly Request $request
    ) {
    }
}
