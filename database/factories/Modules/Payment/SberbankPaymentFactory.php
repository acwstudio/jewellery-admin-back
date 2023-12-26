<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Payment;

use App\Modules\Payment\Models\SberbankPayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JsonException;

/**
 * @method static SberbankPayment create(array $attributes = [])
 */
class SberbankPaymentFactory extends Factory
{
    protected $model = SberbankPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws JsonException
     */
    public function definition(): array
    {
        return [
            'order_number'         => Str::random(32),
            'amount'               => $this->faker->numberBetween(1000, 5000) * 100,
            'currency'             => $this->faker->numberBetween(100, 999),
            'return_url'           => $this->faker->url,
            'fail_url'             => $this->faker->url,
            'description'          => $this->faker->sentence,
            'language'             => $this->faker->languageCode,
            'client_id'            => Str::random(20),
            'page_view'            => $this->faker->randomElement(['MOBILE', 'DESKTOP']),
            'json_params'          => json_encode(
                [
                $this->faker->word => $this->faker->word,
                $this->faker->word => $this->faker->word,
                ],
                JSON_THROW_ON_ERROR,
            ),
            'session_timeout_secs' => $this->faker->randomNumber(9),
            'expiration_date'      => $this->faker->dateTimeBetween('+1 hour', '+2 hour'),
            'features'             => Str::random(10),
            'bank_form_url'        => $this->faker->url,
        ];
    }
}
