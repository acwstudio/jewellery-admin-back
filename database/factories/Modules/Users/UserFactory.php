<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Users;

use App\Modules\Users\Enums\SexTypeEnum;
use App\Modules\Users\Models\User;
use App\Packages\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use libphonenumber\PhoneNumberUtil;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->uuid(),
            'surname' => $this->faker->name(),
            'name' => $this->faker->name(),
            'phone' => PhoneNumberUtil::getInstance()->parse(
                '+79990' . random_int(100000, 999999),
                'RU',
                new PhoneNumber()
            ),
            'sex' => SexTypeEnum::MALE->value,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
