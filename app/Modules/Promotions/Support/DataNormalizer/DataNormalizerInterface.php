<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Support\DataNormalizer;

use Spatie\LaravelData\Data;

interface DataNormalizerInterface
{
    public function normalize($data): Data;
}
