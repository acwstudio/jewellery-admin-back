<?php

declare(strict_types=1);

namespace App\Modules\Live\Pipelines;

use App\Modules\Live\Contracts\Pipelines\LiveProductQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class LiveProductQueryBuilderPipeline extends Pipeline implements LiveProductQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
