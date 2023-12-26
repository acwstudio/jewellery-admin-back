<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Contracts\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface FeatureQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder;
}
