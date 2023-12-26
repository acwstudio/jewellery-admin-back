<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services\Import;

use App\Modules\Collections\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use Psr\Log\LoggerInterface;

class ImportCollectionService
{
    public function __construct(
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
        private readonly DataNormalizerInterface $dataNormalizer,
        private readonly LoggerInterface $logger
    ) {
    }

    public function import(\Closure $closure): void
    {
        $queue = config('import.queues.collections');
        $this->AMQPModuleClient->consume($queue, function (iterable $message) use ($closure) {
            $this->normalizeMessage($message, $closure);
        });
    }

    private function normalizeMessage($message, \Closure $closure): void
    {
        try {
            $data = $this->dataNormalizer->normalize($message);
        } catch (\Throwable $e) {
            $this->logger->warning(
                '[ImportCollectionService] Failed to normalize message',
                [
                    'exception' => $e
                ],
            );
            return;
        }

        $closure($data);
    }
}
