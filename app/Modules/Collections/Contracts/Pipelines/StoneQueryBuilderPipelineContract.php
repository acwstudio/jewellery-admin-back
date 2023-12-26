<?php

declare(strict_types=1);

namespace App\Modules\Collections\Contracts\Pipelines;

use Illuminate\Contracts\Pipeline\Pipeline;

interface StoneQueryBuilderPipelineContract extends Pipeline
{
    public function thenReturn();
}
