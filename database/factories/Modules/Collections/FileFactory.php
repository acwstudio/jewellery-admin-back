<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Collections;

use App\Modules\Collections\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [];
    }
}
