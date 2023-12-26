<?php

declare(strict_types=1);

namespace App\Modules\Collections\Pipelines;

use App\Modules\Collections\Contracts\Pipelines\StoneQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class StoneQueryBuilderPipeline extends Pipeline implements StoneQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
