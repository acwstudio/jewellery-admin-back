<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Collections;

use App\Modules\Collections\Models\Stone;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoneFactory extends Factory
{
    protected $model = Stone::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->text(10)
        ];
    }
}
