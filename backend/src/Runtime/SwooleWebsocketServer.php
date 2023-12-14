<?php

namespace App\Runtime;

use App\Infrastructure\Events\WebsocketCloseEvent;
use App\Infrastructure\Events\WebsocketMessageEvent;
use App\Infrastructure\Events\WebsocketOpenEvent;
use App\Infrastructure\Events\WebsocketPipeMessageEvent;
use Runtime\Swoole\ServerFactory;
use Swoole\Http\Request;
use Swoole\Websocket\Frame;
use Swoole\Websocket\Server;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SwooleWebsocketServer extends ServerFactory
{
    private const DEFAULT_OPTIONS = [
        'host' => '127.0.0.1',
        'port' => 8000,
        'mode' => 2, // SWOOLE_PROCESS
        'sock_type' => 1, // SWOOLE_SOCK_TCP
        'settings' => [],
    ];

    private readonly array $options;
    private ?EventDispatcherInterface $eventDispatcher;

    public static function getDefaultOptions(): array
    {
        return self::DEFAULT_OPTIONS;
    }

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->options = parent::getOptions();
        $this->eventDispatcher = null;
    }

    public function createServer(callable $requestHandler): Server
    {
        $server = new Server(
            $this->options['host'],
            (int) $this->options['port'],
            (int) $this->options['mode'],
            (int) $this->options['sock_type']
        );
        $server->set($this->options['settings']);
        $server->on('request', $requestHandler);

        $server->on('Open', function (Server $server, Request $request) {
            if (null === $this->eventDispatcher) {
                return;
            }
            $this->eventDispatcher->dispatch(new WebsocketOpenEvent($server, $request));
        });

        $server->on('PipeMessage', function (Server $server, int $fd) {
            if (null === $this->eventDispatcher) {
                return;
            }
            $this->eventDispatcher->dispatch(new WebsocketPipeMessageEvent($server, $fd));
        });

        $server->on('Message', function (Server $server, Frame $frame) {
            if (null === $this->eventDispatcher) {
                return;
            }
            $this->eventDispatcher->dispatch(new WebsocketMessageEvent($server, $frame));
        });

        $server->on('Close', function (Server $server, int $fd) {
            if (null === $this->eventDispatcher) {
                return;
            }
            $this->eventDispatcher->dispatch(new WebsocketCloseEvent($server, $fd));
        });

        return $server;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
