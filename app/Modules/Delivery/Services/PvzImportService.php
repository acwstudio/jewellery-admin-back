<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Services;

use App\Modules\Delivery\Support\Pvz\DataNormalizer\PvzDataNormalizerInterface;
use App\Modules\Delivery\Support\Pvz\DataProvider\PvzDataProviderInterface;
use Psr\Log\LoggerInterface;

class PvzImportService
{
    public function __construct(
        private readonly PvzDataProviderInterface   $pvzDataProvider,
        private readonly PvzDataNormalizerInterface $pvzDataNormalizer,
        private readonly LoggerInterface            $logger
    ) {
    }

    public function import(\Closure $closure): void
    {
        $this->pvzDataProvider->import(function (iterable $message) use ($closure) {
            foreach ($message as $row) {
                try {
                    $data = $this->pvzDataNormalizer->normalize($row);
                } catch (\Throwable $e) {
                    $this->logger->warning(
                        '[x] Failed to normalize row data',
                        [
                            'exception' => $e
                        ],
                    );

                    continue;
                }

                $closure($data);
            }
        });
    }
}
