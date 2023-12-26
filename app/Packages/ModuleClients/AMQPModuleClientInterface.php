<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

interface AMQPModuleClientInterface
{
    public function consume(string $queue, \Closure $callback): void;
    public function publish(string $queue, $message): void;
}
