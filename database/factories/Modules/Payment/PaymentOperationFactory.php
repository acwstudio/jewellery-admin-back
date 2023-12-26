<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Payment;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentOperation;
use App\Modules\Payment\Models\PaymentOperationType;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JsonException;

class PaymentOperationFactory extends Factory
{
    protected $model = PaymentOperation::class;

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function definition(): array
    {
        /** @var PaymentOperationType $type */
        $type = PaymentOperationType::query()->get()->random();
        return [
            'user_id'       => User::factory(),
            'payment_id'    => Payment::factory(),
            'type_id'       => $type->id,
            'request_json'  => json_encode(
                [
                $this->faker->word => $this->faker->word,
                $this->faker->word => $this->faker->word,
                ],
                JSON_THROW_ON_ERROR,
            ),
            'response_json' => json_encode(
                [
                $this->faker->word => $this->faker->word,
                $this->faker->word => $this->faker->word,
                ],
                JSON_THROW_ON_ERROR,
            ),
        ];
    }
}
