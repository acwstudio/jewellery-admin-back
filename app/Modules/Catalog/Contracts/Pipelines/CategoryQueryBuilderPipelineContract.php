<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Contracts\Pipelines;

use Illuminate\Contracts\Pipeline\Pipeline;

interface CategoryQueryBuilderPipelineContract extends Pipeline
{
    public function thenReturn();
}
