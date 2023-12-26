<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Users;

use App\Modules\Users\Models\Role;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(RoleEnum::cases()),
        ];
    }
}
