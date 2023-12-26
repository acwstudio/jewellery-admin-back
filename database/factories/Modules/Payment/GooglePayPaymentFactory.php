<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Payment;

use App\Modules\Payment\Models\GooglePayPayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JsonException;

/**
 * @method static Factory create(array $attributes = [])
 */
class GooglePayPaymentFactory extends Factory
{
    protected $model = GooglePayPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws JsonException
     */
    public function definition(): array
    {
        return [
            'order_number'          => Str::random(32),
            'description'           => $this->faker->sentence,
            'language'              => $this->faker->languageCode,
            'additional_parameters' => json_encode([
                $this->faker->word => $this->faker->word,
                $this->faker->word => $this->faker->word,
            ], JSON_THROW_ON_ERROR),
            'pre_auth'              => $this->faker->randomElement(['true', 'false']),
            'client_id'             => Str::random(30),
            'ip'                    => $this->faker->ipv6,
            'amount'                => $this->faker->numberBetween(),
            'currency_code'         => (string)$this->faker->numberBetween(100, 999),
            'email'                 => $this->faker->email,
            'phone'                 => $this->faker->phoneNumber,
            'return_url'            => $this->faker->url,
            'fail_url'              => $this->faker->url,
            'payment_token'         => Str::random(100),
        ];
    }
}
