<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\Filter;

use App\Modules\Delivery\Models\Pvz;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class StreetFilterPipe implements FilterPipeInterface
{
    public function __invoke(Passable $passable, Closure $next): Collection
    {
        if ($passable->filter->address?->street === null) {
            return $next($passable);
        }

        $filtered = $passable->pvz->filter(function (Pvz $pvz) use ($passable) {
            return $this->match(
                $passable->filter->address->street,
                $pvz->street
            );
        });

        return $filtered;
    }

    private function match(string $filter, string $street): bool
    {
        return Str::contains(Str::lower($street), Str::lower($filter));
    }
}
