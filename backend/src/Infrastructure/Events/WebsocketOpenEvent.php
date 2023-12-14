<?php

namespace App\Infrastructure\Events;

use App\Port\Inbound\InboundPortInterface;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use Symfony\Contracts\EventDispatcher\Event;

class WebsocketOpenEvent extends Event implements InboundPortInterface
{
    public function __construct(
        public readonly Server $server,
        public readonly Request $request
    ) {
    }
}
