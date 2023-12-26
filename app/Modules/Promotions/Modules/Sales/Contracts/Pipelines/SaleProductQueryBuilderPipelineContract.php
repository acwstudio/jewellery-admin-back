<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Contracts\Pipelines;

use Illuminate\Contracts\Pipeline\Pipeline;

interface SaleProductQueryBuilderPipelineContract extends Pipeline
{
    public function thenReturn();
}
