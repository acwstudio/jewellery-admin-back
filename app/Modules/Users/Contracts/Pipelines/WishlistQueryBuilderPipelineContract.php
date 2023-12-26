<?php

declare(strict_types=1);

namespace App\Modules\Users\Contracts\Pipelines;

use Illuminate\Contracts\Pipeline\Pipeline;

interface WishlistQueryBuilderPipelineContract extends Pipeline
{
    public function thenReturn();
}
