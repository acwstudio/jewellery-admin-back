<?php

declare(strict_types=1);

namespace App\Modules\Live\Contracts\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface LiveProductQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder;
}
