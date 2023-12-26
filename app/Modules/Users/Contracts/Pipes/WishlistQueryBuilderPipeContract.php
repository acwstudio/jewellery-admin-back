<?php

declare(strict_types=1);

namespace App\Modules\Users\Contracts\Pipes;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface WishlistQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder;
}
