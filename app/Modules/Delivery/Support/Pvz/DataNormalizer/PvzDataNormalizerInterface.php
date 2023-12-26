<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\DataNormalizer;

use App\Packages\DataObjects\Delivery\ImportPvzData;

interface PvzDataNormalizerInterface
{
    public function normalize(array $rawData): ImportPvzData;
}
