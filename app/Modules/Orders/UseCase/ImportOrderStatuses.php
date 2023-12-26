<?php

declare(strict_types=1);

namespace App\Modules\Orders\UseCase;

use App\Modules\Orders\Services\Import\ImportOrderStatusesService;
use App\Modules\Orders\Services\OrderService;
use App\Packages\DataObjects\Orders\Import\ImportOrderStatusData;
use App\Packages\DataObjects\Orders\Order\UpdateOrderData;
use Psr\Log\LoggerInterface;

class ImportOrderStatuses
{
    public function __construct(
        private readonly ImportOrderStatusesService $importOrderStatusesService,
        private readonly OrderService $orderService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        $this->importOrderStatusesService->import(function (ImportOrderStatusData $data) use ($onEach) {
            try {
                $this->upsert($data);
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Order Status import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        });
    }

    private function upsert(ImportOrderStatusData $data): void
    {
        $order = $this->orderService->get($data->order_id);
        $this->orderService->update($order, new UpdateOrderData(
            id: $order->id,
            status: $data->status,
            status_date: $data->date_time,
            external_id: $data->external_id
        ));
    }
}
