<?php

declare(strict_types=1);

namespace App\Modules\Collections\Support\DataNormalizer;

use Spatie\LaravelData\Data;

interface DataNormalizerInterface
{
    public function normalize(array $data): Data;
}
