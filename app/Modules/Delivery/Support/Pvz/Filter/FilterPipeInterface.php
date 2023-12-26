<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\Filter;

use Closure;
use Illuminate\Support\Collection;

interface FilterPipeInterface
{
    public function __invoke(Passable $passable, Closure $next): Collection;
}
