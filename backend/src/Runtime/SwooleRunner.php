<?php

namespace App\Runtime;

use Runtime\Swoole\SymfonyRunner;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SwooleRunner extends SymfonyRunner
{
    private ConsoleOutput $output;

    public function __construct(
        private readonly SwooleWebsocketServer $serverFactory,
        private readonly HttpKernelInterface $application
    ) {
        parent::__construct($this->serverFactory, $this->application);
        $this->output = new ConsoleOutput();
    }

    public function run(): int
    {
        $server = $this->serverFactory->createServer([$this, 'handle']);

        $server->on('WorkerStart', function () use ($server) {
            $this->application->boot();
            $this->application->getContainer()->set('websocket.server', $server);
            $this->serverFactory->setEventDispatcher($this->application->getContainer()->get('event_dispatcher'));
        });

        $server->on('Start', function () use ($server) {
            $this->output->writeln('Websocket is now listening in '.$server->host.':'.$server->port);
        });

        $server->start();

        return 0;
    }
}
