<?php

declare(strict_types=1);

namespace App\Console\Commands\Orders;

use App\Console\Command;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Filter\BetweenDatetimeData;
use App\Packages\DataObjects\Orders\Filter\FilterOrderData;
use App\Packages\DataObjects\Orders\Order\GetOrderListData;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PublishOrders extends Command
{
    protected $signature = 'publish:orders {dateStart} {dateEnd}';
    protected $description = 'Отправка на публикацию заказов';

    public function __construct(
        private readonly OrdersModuleClientInterface $ordersModuleClient
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Start publishing orders...');

        $start = $this->carbonFormat($this->argument('dateStart'));
        $end = $this->carbonFormat($this->argument('dateEnd'));
        $orders = $this->getOrders($start, $end);

        $this->withoutTelescope(function () use ($orders) {
            $this->publishOrders($orders);
        });

        $this->info("\nOrders published!");

        return Command::SUCCESS;
    }

    private function getOrders(Carbon $start, Carbon $end): Collection
    {
        $orderCollection = new Collection();
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $data = new GetOrderListData(
                pagination: new PaginationData($page, 100),
                filter: new FilterOrderData(
                    between_datetime: new BetweenDatetimeData($start, $end)
                )
            );

            $orderListData = $this->ordersModuleClient->getOrders($data);
            $orderCollection = $orderCollection->merge($orderListData->items->all());

            $this->printInfoOrders($orderListData->pagination, $orderCollection);

            $isRepeat = $orderListData->pagination->last_page > $orderListData->pagination->page;
            $page++;
        }

        return $orderCollection;
    }

    private function publishOrders(Collection $orders): void
    {
        /** @var \App\Packages\DataObjects\Orders\Order\OrderData $order */
        foreach ($orders as $order) {
            try {
                $this->ordersModuleClient->publishOrder($order->id);
            } catch (\Throwable $e) {
                $this->error("\n[PublishOrders] Order {$order->id}");
            }
        }
    }

    private function carbonFormat(string $date): Carbon
    {
        return Carbon::parse($date);
    }

    private function printInfoOrders(PaginationData $paginationData, Collection $orders): void
    {
        $p1 = $paginationData->page;
        $p2 = $paginationData->per_page;
        $p3 = $paginationData->last_page;
        $p4 = $paginationData->total;
        $p5 = $orders->count();

        $info = "[PublishOrders] Get Orders [page: {$p1}, perPage: {$p2}, lastPage: {$p3}, total: {$p4}, count: {$p5}]";
        $this->info("\n{$info}");
    }
}
