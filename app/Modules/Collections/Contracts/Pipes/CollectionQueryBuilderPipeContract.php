<?php

declare(strict_types=1);

namespace App\Modules\Collections\Contracts\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface CollectionQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder;
}
