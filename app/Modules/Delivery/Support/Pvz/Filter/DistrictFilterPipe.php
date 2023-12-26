<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\Filter;

use App\Modules\Delivery\Models\Pvz;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DistrictFilterPipe implements FilterPipeInterface
{
    public function __invoke(Passable $passable, Closure $next): Collection
    {
        if ($passable->filter->address?->district === null) {
            return $next($passable);
        }

        $filtered = $passable->pvz->filter(function (Pvz $pvz) use ($passable) {
            return $this->match(
                $passable->filter->address->district,
                $pvz->district
            );
        });

        return $filtered;
    }

    private function match(string $filter, string $district): bool
    {
        return Str::contains(Str::lower($district), Str::lower($filter));
    }
}
