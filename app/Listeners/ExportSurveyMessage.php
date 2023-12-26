<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\Sync\SendSurvey1C;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExportSurveyMessage
{
    public function __construct(
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(SendSurvey1C $event): void
    {
        try {
            $queue = config('export.queues.surveys');
            $this->AMQPModuleClient->publish($queue, $event->message);
            $this->logger->info('[ExportSurvey] Successfully send message');
        } catch (Throwable $e) {
            $this->logger->alert('[ExportSurvey] Failed send message', [
                'exception' => $e
            ]);
        }
    }
}
