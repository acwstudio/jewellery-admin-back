<?php

declare(strict_types=1);

namespace Domain\Blog\Pipelines\BlogPost;

use Domain\AbstractPipeline;
use Domain\Blog\Pipelines\BlogPost\Pipes\BlogPostDestroyPipe;
use Domain\Blog\Pipelines\BlogPost\Pipes\BlogPostsBlogCategoryStoreUpdateRelationshipsPipe;
use Domain\Blog\Pipelines\BlogPost\Pipes\BlogPostStorePipe;
use Domain\Blog\Pipelines\BlogPost\Pipes\BlogPostUpdatePipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class BlogPostPipeline extends AbstractPipeline
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
                    BlogPostStorePipe::class,
                    BlogPostsBlogCategoryStoreUpdateRelationshipsPipe::class
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
                    BlogPostUpdatePipe::class,
                    BlogPostsBlogCategoryStoreUpdateRelationshipsPipe::class
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
                    BlogPostDestroyPipe::class
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
