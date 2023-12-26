<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Payment;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentOperation;
use App\Modules\Payment\Models\PaymentOperationType;
use App\Modules\Payment\Models\PaymentStatus;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use JsonException;

class PaymentStatusFactory extends Factory
{
    protected $model = PaymentStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'bank_id'   => random_int(0, 6),
            'name'      => fake()->word(),
            'full_name' => fake()->word(),
            'is_active' => true,
        ];
    }
}
