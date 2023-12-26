<?php

declare(strict_types=1);

namespace App\Modules\Collections\Pipelines;

use App\Modules\Collections\Contracts\Pipelines\CollectionQueryBuilderPipelineContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class CollectionQueryBuilderPipeline extends Pipeline implements CollectionQueryBuilderPipelineContract
{
    public function __construct(Container $container = null, array $pipes = [])
    {
        parent::__construct($container);
        $this->pipes = $pipes;
    }
}
