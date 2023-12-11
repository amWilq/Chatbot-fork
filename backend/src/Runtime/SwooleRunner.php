<?php

namespace App\Runtime;

use Runtime\Swoole\SymfonyRunner;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SwooleRunner extends SymfonyRunner
{
    public function __construct(
        private readonly SwooleWebsocketServer $serverFactory,
        private readonly HttpKernelInterface $application
    ) {
        parent::__construct($this->serverFactory, $this->application);
    }

    public function run(): int
    {
        $this->serverFactory->createServer([$this, 'handle'])->start();
        $this->serverFactory->setEventDispatcher($this->application->getContainer()->get('event_dispatcher'));

        return 0;
    }
}
