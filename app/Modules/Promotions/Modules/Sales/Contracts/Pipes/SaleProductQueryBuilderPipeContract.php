<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Contracts\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface SaleProductQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder;
}
