<?php

declare(strict_types=1);

namespace App\Modules\Orders\Repositories;

use App\Modules\Orders\Contracts\Pipelines\OrderQueryBuilderPipelineContract;
use App\Modules\Orders\Models\Order;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Order\UpdateOrderData;
use App\Packages\Enums\Orders\OrderStatusEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Money\Money;

class OrderRepository
{
    public function get(int $id): Order
    {
        return Order::findOrFail($id);
    }

    public function getByUserId(int $id, string $userId, bool $fail = false): ?Order
    {
        /** @var Order|null $model */
        $model = Order::query()
            ->where('id', '=', $id)
            ->where('user_id', '=', $userId)
            ->get()
            ->first();

        if ($fail && null === $model) {
            throw (new ModelNotFoundException())->setModel(Order::class);
        }

        return $model;
    }

    public function getPaginatedList(?PaginationData $pagination = null, bool $fail = false): LengthAwarePaginator
    {
        $query = Order::query();

        /** @var OrderQueryBuilderPipelineContract $pipeline */
        $pipeline = app(OrderQueryBuilderPipelineContract::class);

        $paginator = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination?->per_page, ['*'], 'page', $pagination?->page);

        if ($fail && $paginator->total() === 0) {
            throw (new ModelNotFoundException())->setModel(Order::class);
        }

        return $paginator;
    }

    public function create(
        ?User $user,
        string $project,
        string $country,
        string $currency,
        PaymentTypeEnum $paymentTypeEnum,
        Money $summary,
        ?string $comment,
        ?string $promotionExternalId,
        ?string $shopCartToken
    ): Order {
        /** @var Order $order */
        $order = $user?->orders()->create([
            'project' => $project,
            'country' => $country,
            'currency' => $currency,
            'summary' => $summary,
            'comment' => $comment,
            'payment_type' => $paymentTypeEnum,
            'promotion_external_id' => $promotionExternalId,
            'shop_cart_token' => $shopCartToken,
            'status' => OrderStatusEnum::CREATED,
            'status_date' => Carbon::now()
        ]);

        return $order;
    }

    public function update(Order $order, UpdateOrderData $data): void
    {
        $order->update([
            'status' => $data->status,
            'status_date' => $data->status_date ?? Carbon::now(),
            'external_id' => $data->external_id
        ]);
    }
}
