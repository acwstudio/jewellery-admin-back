<?php

declare(strict_types=1);

namespace App\Modules\Orders\Contracts\Pipelines;

use Illuminate\Contracts\Pipeline\Pipeline;

interface OrderQueryBuilderPipelineContract extends Pipeline
{
    public function thenReturn();
}
