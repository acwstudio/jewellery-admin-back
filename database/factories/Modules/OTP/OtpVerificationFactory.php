<?php

declare(strict_types=1);

namespace Database\Factories\Modules\OTP;

use App\Modules\OTP\Models\OtpVerification;
use App\Packages\Support\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use libphonenumber\PhoneNumberUtil;

class OtpVerificationFactory extends Factory
{
    protected $model = OtpVerification::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'code' => (string)$this->faker->randomDigitNotNull(),
            'phone' => PhoneNumberUtil::getInstance()->parse(
                '+79990123456',
                'RU',
                new PhoneNumber()
            ),
            'confirmed' => false,
            'created_at' => Carbon::now()
        ];
    }
}
