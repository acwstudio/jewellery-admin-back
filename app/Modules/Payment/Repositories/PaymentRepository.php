<?php

declare(strict_types=1);

namespace App\Modules\Payment\Repositories;

use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PaymentRepository
{
    public function all(): array
    {
        $queryBuilder = Payment::query();
        return $queryBuilder->get()->all();
    }

    public function getById(int $id, array $columns = ['*'], bool $fail = false): ?Payment
    {
        if ($fail) {
            /** @var Payment $payment */
            $payment = Payment::query()->findOrFail($id, $columns);
            return $payment;
        }
        /** @var Payment $payment */
        $payment = Payment::query()->where('id', $id)->first($columns);
        return $payment;
    }

    public function find(int $id, array $columns = ['*'], bool $fail = false): ?Payment
    {
        return Payment::query()->when($fail, function (
            Builder $query
        ) use (
            $id,
            $columns
        ) {
            /** @var Payment $payment */
            $payment = $query->where('id', $id)->findOrFail($columns);
            return $payment;
        }, function (
            $query
        ) use (
            $id,
            $columns
        ) {
            /** @var Payment $payment */
            $payment = $query->where('id', $id)->first($columns);
            return $payment;
        })->first($columns);
    }

    public function getByStatus(array $statuses, array $columns = ['*']): Collection
    {
        return Payment::query()->whereIn('status_id', $statuses)->get($columns);
    }

    public function getByBankOrderId(string $bankOrderId, bool $fail = false): ?Payment
    {
        /** @var Payment|null $model */
        $model = Payment::query()
            ->where('bank_order_id', '=', $bankOrderId)
            ->get()
            ->first();

        if ($fail && null === $model) {
            throw (new ModelNotFoundException())->setModel(Payment::class);
        }

        return $model;
    }

    public function updateStatus(Payment $payment, PaymentStatusEnum $status): void
    {
        $payment->update([
            'status_id' => $status->value
        ]);
    }
}
