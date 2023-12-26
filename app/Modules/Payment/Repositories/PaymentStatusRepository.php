<?php

declare(strict_types=1);

namespace App\Modules\Payment\Repositories;

use App\Modules\Payment\Models\PaymentStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentStatusRepository
{
    /**
     * Find model by ID in the bank system
     *
     * @param int $bankId ID of the status in the bank system
     * @param array|null $columns Columns to retrieve from the model
     *
     * @return PaymentStatus|null
     */
    public function findByBankId(int $bankId, ?array $columns = null): ?PaymentStatus
    {
        return PaymentStatus::query()->where('bank_id', $bankId)->first($columns);
    }

    /**
     * Find a payment status by ID.
     *
     * @param int $id The ID of the payment status.
     * @param array|string[] $columns The columns to retrieve from the payment status table. Default is all columns.
     * @param bool $fail Whether to throw an exception if the payment status is not found. Default is false.
     *
     * @return PaymentStatus|null The found payment status, or null if not found and $fail is false.
     * @throws ModelNotFoundException If the payment status is not found and $fail is true.
     */
    public function find(int $id, array $columns = ['*'], bool $fail = false): ?PaymentStatus
    {
        $query = PaymentStatus::query();
        if ($fail) {
            /** @var PaymentStatus $paymentStatus */
            $paymentStatus = $query->findOrFail($id, $columns);
            return $paymentStatus;
        }
        /** @var PaymentStatus $paymentStatus */
        $paymentStatus = $query->find($id, $columns);
        return $paymentStatus;
    }
}
