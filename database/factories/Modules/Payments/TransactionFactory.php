<?php

namespace Database\Factories\Modules\Payments;

use App\Modules\Payments\Models\Transaction;
use App\Packages\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_id' => fake()->numberBetween(100000, 999999),
            'status' => fake()->randomElement(TransactionStatusEnum::cases()),
            'amount' => Money::RUB(fake()->numberBetween(1000, 100000)),
            'total' => Money::RUB(fake()->numberBetween(1000, 100000)),
            'fiscalized' => fake()->boolean(),
            'fiscalized_at' => fake()->dateTime(),
        ];
    }

    public function withStatus(TransactionStatusEnum $status): self
    {
        return $this->state(function () use ($status) {
            return [
                'status' => $status
            ];
        });
    }

    public function withOrderId(int $orderId): self
    {
        return $this->state(function () use ($orderId) {
            return [
                'order_id' => $orderId
            ];
        });
    }
}
