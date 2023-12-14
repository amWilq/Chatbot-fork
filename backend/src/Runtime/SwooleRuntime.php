<?php

namespace App\Runtime;

use Runtime\Swoole\Runtime;
use Runtime\Swoole\ServerFactory;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Runtime\RunnerInterface;

class SwooleRuntime extends Runtime
{
    private SwooleWebsocketServer $websocketServer;

    public function __construct(array $options, ServerFactory $websocketServer = null)
    {
        $this->websocketServer = $websocketServer ?? new SwooleWebsocketServer($options);
        parent::__construct($options, $this->websocketServer);
    }

    public function getRunner(?object $application): RunnerInterface
    {
        if ($application instanceof HttpKernelInterface) {
            return new SwooleRunner($this->websocketServer, $application);
        }

        return parent::getRunner($application);
    }
}
