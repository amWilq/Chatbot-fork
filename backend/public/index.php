<?php

use App\Kernel;
use Swoole\Constant;
use App\Runtime\SwooleRuntime;

if ($_ENV['SWOOLE_RUNTIME'] ?? false) {
    $_SERVER['APP_RUNTIME'] = SwooleRuntime::class;

    $_SERVER['APP_RUNTIME_OPTIONS'] = [
      'host' => '0.0.0.0',
      'port' => 80,
      'mode' => SWOOLE_BASE,
      'settings' => [
        Constant::OPTION_WORKER_NUM => swoole_cpu_num() * 2,
        Constant::OPTION_ENABLE_STATIC_HANDLER => true,
        Constant::OPTION_DOCUMENT_ROOT => dirname(__DIR__).'/public',
      ],
    ];
}

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
