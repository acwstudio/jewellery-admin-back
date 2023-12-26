<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services\Import;

use App\Packages\DataObjects\Orders\Import\ImportOrderStatusData;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use Psr\Log\LoggerInterface;

class ImportOrderStatusesService
{
    public function __construct(
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function import(\Closure $closure): void
    {
        $queue = config('import.queues.order_statuses');
        $this->AMQPModuleClient->consume($queue, function (iterable $message) use ($closure) {
            $this->normalizeMessage($message, $closure);
        });
    }

    private function normalizeMessage($message, \Closure $closure): void
    {
        try {
            $data = ImportOrderStatusData::from($message);
        } catch (\Throwable $e) {
            $this->logger->warning(
                '[x] Failed to normalize message',
                [
                    'exception' => $e
                ],
            );
            return;
        }

        $closure($data);
    }
}
