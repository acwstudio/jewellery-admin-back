<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\Filter;

use App\Modules\Delivery\Models\Pvz;
use App\Packages\DataObjects\Delivery\GetPvz\GetPvzFilterData;
use Illuminate\Support\Collection;

class Passable
{
    /**
     * @param Collection<Pvz> $pvz
     */
    public function __construct(
        public readonly Collection $pvz,
        public readonly GetPvzFilterData $filter
    ) {
    }
}
