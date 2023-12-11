<?php

namespace App\Application\Services;

use Swoole\Client;
use Swoole\Client\Exception;

class WebsocketService
{
    /** @var Client[] */
    private array $connections = [];

    public function __construct(
        private readonly string $host,
        private readonly int $port
    ) {
    }

    public function openConnection(string $userId, $connection): void
    {
        $this->connections[$userId] = $connection;
    }

    /**
     * @throws Exception
     */
    public function connect(string $userId): void
    {
        $connection = new Client(SWOOLE_SOCK_TCP);
        $connection->connect($this->host, $this->port);

        if (!$connection->isConnected()) {
            throw new Client\Exception('Connection failed!', SWOOLE_ERROR_WEBSOCKET_HANDSHAKE_FAILED);
        }

        $connection->send('Handshake successful!');
        $this->openConnection($userId, $connection);
    }
}
