<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypePage;

use Domain\AbstractPipeline;
use Domain\Performance\Pipelines\TypePage\Pipes\TypePageBannersUpdateRelationshipsPipe;
use Domain\Performance\Pipelines\TypePage\Pipes\TypePageDestroyPipe;
use Domain\Performance\Pipelines\TypePage\Pipes\TypePageStorePipe;
use Domain\Performance\Pipelines\TypePage\Pipes\TypePageUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TypePagePipeline extends AbstractPipeline
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
                    TypePageStorePipe::class,
                    TypePageBannersUpdateRelationshipsPipe::class,
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
                    TypePageUpdatePipe::class,
                    TypePageBannersUpdateRelationshipsPipe::class,
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
                    TypePageDestroyPipe::class
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
