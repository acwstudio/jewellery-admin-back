<?php

namespace App\Modules\Catalog\Support\DataNormalizer;

use Spatie\LaravelData\Data;

interface DataNormalizerInterface
{
    public function normalize($data): Data;
}
