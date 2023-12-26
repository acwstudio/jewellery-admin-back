<?php

declare(strict_types=1);

namespace App\Modules\Orders\Contracts\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface OrderQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder;
}
