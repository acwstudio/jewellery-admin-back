<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Payment;

use App\Modules\Payment\Enums\PaymentSystemTypeEnum;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Models\ApplePayPayment;
use App\Modules\Payment\Models\GooglePayPayment;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentStatus;
use App\Modules\Payment\Models\SamsungPayPayment;
use App\Modules\Payment\Models\SberbankPayment;
use Database\Factories\CoreFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @inheritDoc
 */
class PaymentFactory extends CoreFactory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var PaymentStatus $status */
        $status = PaymentStatus::query()->get()->random();
        return [
            'bank_order_id' => fake()->uuid(),
            'system_id'     => PaymentSystemTypeEnum::SBERBANK->value,
            'status_id'     => $status->id,
            'payment_type'  => SberbankPayment::class,
            'payment_id'    => SberbankPayment::factory(),
        ];
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function sberPay(): Factory
    {
        return $this->state($this->sberPaymentCallable());
    }

    public function getCallable(PaymentTypeEnum $type): callable
    {
        return match ($type) {
            PaymentTypeEnum::SBER_PAY => $this->sberPaymentCallable(),
            PaymentTypeEnum::APPLE_PAY => $this->applePayCallable(),
            PaymentTypeEnum::SAMSUNG_PAY => $this->samsungPayCallable(),
            PaymentTypeEnum::GOOGLE_PAY => $this->googlePayCallable(),
            default => fn() => $this->sberPaymentCallable(),
        };
    }

    public function sberPaymentCallable(): callable
    {
        return function (array $attributes) {
            /** @var PaymentStatus $status */
            $status = PaymentStatus::query()->get()->random();
            return [
                'bank_order_id' => fake()->uuid(),
                'system_id'     => PaymentSystemTypeEnum::SBERBANK->value,
                'status_id'     => $status->id,
                'payment_type'  => SberbankPayment::class,
                'payment_id'    => SberbankPayment::factory(),
            ];
        };
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function applePay(): Factory
    {
        return $this->state($this->applePayCallable());
    }

    public function applePayCallable(): callable
    {
        return function (array $attributes) {
            return [
                'system_id'    => PaymentSystemTypeEnum::APPLE_PAY->value,
                'payment_type' => ApplePayPayment::class,
                'payment_id'   => ApplePayPayment::factory(),
            ];
        };
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function googlePay(): Factory
    {
        return $this->state($this->googlePayCallable());
    }

    public function googlePayCallable(): callable
    {
        return function (array $attributes) {
            return [
                'system_id'    => PaymentSystemTypeEnum::GOOGLE_PAY->value,
                'payment_type' => GooglePayPayment::class,
                'payment_id'   => GooglePayPayment::factory(),
            ];
        };
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function samsungPay(): Factory
    {
        return $this->state($this->samsungPayCallable());
    }

    public function samsungPayCallable(): callable
    {
        return function (array $attributes) {
            return [
                'system_id'    => PaymentSystemTypeEnum::SAMSUNG_PAY->value,
                'payment_type' => SamsungPayPayment::class,
                'payment_id'   => SamsungPayPayment::factory(),
            ];
        };
    }
}
