<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeDevice;

use Domain\AbstractPipeline;
use Domain\Performance\Pipelines\TypeDevice\Pipes\TypeDeviceDestroyPipe;
use Domain\Performance\Pipelines\TypeDevice\Pipes\TypeDeviceImageBannersUpdateRelationshipsPipe;
use Domain\Performance\Pipelines\TypeDevice\Pipes\TypeDeviceStorePipe;
use Domain\Performance\Pipelines\TypeDevice\Pipes\TypeDeviceUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TypeDevicePipeline extends AbstractPipeline
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
                    TypeDeviceStorePipe::class,
                    TypeDeviceImageBannersUpdateRelationshipsPipe::class,
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
                    TypeDeviceUpdatePipe::class,
                    TypeDeviceImageBannersUpdateRelationshipsPipe::class,
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
                    TypeDeviceDestroyPipe::class
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
