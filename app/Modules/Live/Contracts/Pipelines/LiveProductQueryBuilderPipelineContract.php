<?php

declare(strict_types=1);

namespace App\Modules\Live\Contracts\Pipelines;

use Illuminate\Contracts\Pipeline\Pipeline;

interface LiveProductQueryBuilderPipelineContract extends Pipeline
{
    public function thenReturn();
}
