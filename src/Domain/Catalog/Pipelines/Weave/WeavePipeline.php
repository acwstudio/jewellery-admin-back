<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Weave;

use Domain\AbstractPipeline;
use Illuminate\Database\Eloquent\Model;

final class WeavePipeline extends AbstractPipeline
{

    public function store(array $data): Model
    {
        // TODO: Implement store() method.
    }

    public function update(array $data): void
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id): void
    {
        // TODO: Implement destroy() method.
    }
}