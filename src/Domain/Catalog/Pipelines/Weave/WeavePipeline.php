<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Weave;

use Domain\AbstractPipeline;
use Domain\Catalog\Pipelines\Weave\Pipes\WeaveDestroyPipe;
use Domain\Catalog\Pipelines\Weave\Pipes\WeavesProductsStoreUpdateRelationshipsPipe;
use Domain\Catalog\Pipelines\Weave\Pipes\WeaveStorePipe;
use Domain\Catalog\Pipelines\Weave\Pipes\WeaveUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class WeavePipeline extends AbstractPipeline
{

    /**
     * @throws \Throwable
     */
    public function store(array $data): Model
    {
        try {
            DB::beginTransaction();

            $data = $this->pipeline
                ->send($data)
                ->through([
                    WeaveStorePipe::class,
                    WeavesProductsStoreUpdateRelationshipsPipe::class
                ])
                ->thenReturn();

            DB::commit();

            return data_get($data, 'model');
        } catch (\Exception | \Throwable $e) {
            DB::rollBack();
            Log::error($e);

            throw ($e);
        }
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data): void
    {
        try {
            DB::beginTransaction();

            $this->pipeline
                ->send($data)
                ->through([
                    WeaveUpdatePipe::class,
                    WeavesProductsStoreUpdateRelationshipsPipe::class,
                ])
                ->thenReturn();

            \DB::commit();
        } catch (\Exception | \Throwable $e) {
            \DB::rollBack();
            \Log::error($e);

            throw ($e);
        }
    }

    /**
     * @throws \Throwable
     */
    public function destroy(int $id): void
    {
        try {
            DB::beginTransaction();

            $this->pipeline
                ->send($id)
                ->through([
                    WeaveDestroyPipe::class
                ])
                ->thenReturn();

            \DB::commit();
        } catch (\Exception | \Throwable $e) {
            \DB::rollBack();
            \Log::error($e);

            throw ($e);
        }
    }
}
