<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\Filter;

use App\Modules\Delivery\Models\Pvz;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CarrierFilterPipe implements FilterPipeInterface
{
    public function __invoke(Passable $passable, Closure $next): Collection
    {
        if ($passable->filter->carrierIds === null) {
            return $next($passable);
        }

        $filtered = $passable->pvz->filter(function (Pvz $pvz) use ($passable) {
            return collect($passable->filter->carrierIds)->contains($pvz->carrier->id);
        });

        return $next(
            new Passable($filtered, $passable->filter)
        );
    }
}
