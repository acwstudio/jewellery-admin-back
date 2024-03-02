<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\Banner;

use Domain\AbstractPipeline;
use Domain\Performance\Pipelines\Banner\Pipes\BannerDestroyPipe;
use Domain\Performance\Pipelines\Banner\Pipes\BannersImageBannersUpdateRelationshipsPipe;
use Domain\Performance\Pipelines\Banner\Pipes\BannerStorePipe;
use Domain\Performance\Pipelines\Banner\Pipes\BannersTypeBannerUpdateRelationshipsPipe;
use Domain\Performance\Pipelines\Banner\Pipes\BannerUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class BannerPipeline extends AbstractPipeline
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
                    BannerStorePipe::class,
                    BannersImageBannersUpdateRelationshipsPipe::class,
                    BannersTypeBannerUpdateRelationshipsPipe::class
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
                    BannerUpdatePipe::class,
                    BannersImageBannersUpdateRelationshipsPipe::class,
                    BannersTypeBannerUpdateRelationshipsPipe::class
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
                    BannerDestroyPipe::class
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
