<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Repositories\OrderRepository;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Order\UpdateOrderData;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Money\Money;

class OrderService
{
    public function __construct(
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function get(int $id): Order
    {
        return $this->orderRepository->get($id);
    }

    public function getByUserId(int $id, string $userId): ?Order
    {
        return $this->orderRepository->getByUserId($id, $userId);
    }

    public function all(?PaginationData $pagination = null): LengthAwarePaginator
    {
        return $this->orderRepository->getPaginatedList($pagination);
    }

    public function create(
        string $project,
        string $country,
        string $currency,
        PaymentTypeEnum $paymentTypeEnum,
        Money $summary,
        ?string $comment,
        ?string $promotionExternalId,
        ?string $shopCartToken
    ): Order {
        $user = $this->usersModuleClient->getUser();

        return $this->orderRepository->create(
            $user,
            $project,
            $country,
            $currency,
            $paymentTypeEnum,
            $summary,
            $comment,
            $promotionExternalId,
            $shopCartToken
        );
    }

    public function update(Order|int $order, UpdateOrderData $data): Order
    {
        if (is_int($order)) {
            $order = $this->orderRepository->get($order);
        }

        $this->orderRepository->update($order, $data);

        return $order->refresh();
    }
}
