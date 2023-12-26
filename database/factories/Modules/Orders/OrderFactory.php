<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Orders;

use App\Modules\Orders\Models\Order;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Orders\OrderStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'project' => 'UVI',
            'country' => 'RU',
            'currency' => 'RUB',
            'summary' => Money::RUB(rand(1000, 1000) * 100),
            'user_id' => User::factory(),
            'payment_type' => PaymentTypeEnum::CASH,
            'status' => OrderStatusEnum::CREATED,
            'status_date' => Carbon::now()
        ];
    }
}
