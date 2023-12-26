<?php

declare(strict_types=1);

namespace App\Modules\Live\Repositories;

use App\Modules\Live\Support\Blueprints\LiveProductBlueprint;
use App\Modules\Live\Support\Pagination;
use App\Modules\Live\Contracts\Pipelines\LiveProductQueryBuilderPipelineContract;
use App\Modules\Live\Models\LiveProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class LiveProductRepository
{
    public function getById(int $id, bool $fail = false): ?LiveProduct
    {
        if ($fail) {
            return LiveProduct::findOrFail($id);
        }

        return LiveProduct::find($id);
    }

    public function getByProductId(int $productId, bool $fail = false): ?LiveProduct
    {
        /** @var LiveProduct|null $model */
        $model = LiveProduct::query()->where('product_id', '=', $productId)->first();

        if ($fail && !$model instanceof LiveProduct) {
            throw (new ModelNotFoundException())->setModel(LiveProduct::class);
        }

        return $model;
    }

    public function getList(): Collection
    {
        $query = LiveProduct::query();
        /** @var LiveProductQueryBuilderPipelineContract $pipeline */
        $pipeline = app(LiveProductQueryBuilderPipelineContract::class);
        return $pipeline->send($query)->thenReturn()->get();
    }

    public function getPaginatedList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = LiveProduct::query();

        /** @var LiveProductQueryBuilderPipelineContract $pipeline */
        $pipeline = app(LiveProductQueryBuilderPipelineContract::class);

        /** @var LengthAwarePaginator $liveProducts */
        $liveProducts = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $liveProducts->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $liveProducts;
    }

    public function create(LiveProductBlueprint $liveProductBlueprint): LiveProduct
    {
        $number = $liveProductBlueprint->number;
        if ($number < 0) {
            $number = 0;
        }

        $product = new LiveProduct([
            'product_id' => $liveProductBlueprint->product_id,
            'number' => $number,
            'started_at' => $liveProductBlueprint->started_at,
            'expired_at' => $liveProductBlueprint->expired_at,
            'on_live' => $liveProductBlueprint->on_live ?? false
        ]);

        $product->save();

        return $product;
    }

    public function update(LiveProduct $liveProduct, LiveProductBlueprint $liveProductBlueprint): void
    {
        $data = [
            'started_at' => $liveProductBlueprint->started_at,
            'expired_at' => $liveProductBlueprint->expired_at
        ];

        if ($liveProductBlueprint->number >= 0) {
            $data['number'] = $liveProductBlueprint->number;
        }

        if (null !== $liveProductBlueprint->on_live) {
            $data['on_live'] = $liveProductBlueprint->on_live;
        }

        $liveProduct->update($data);
    }

    public function createOrUpdate(LiveProductBlueprint $liveProductBlueprint): LiveProduct
    {
        $liveProduct = LiveProduct::query()
            ->where('product_id', '=', $liveProductBlueprint->product_id)
            ->get()
            ->first();

        if ($liveProduct instanceof LiveProduct) {
            $this->update($liveProduct, $liveProductBlueprint);
            return $liveProduct->refresh();
        }

        return $this->create($liveProductBlueprint);
    }

    public function unsetOnLive(): void
    {
        LiveProduct::query()
            ->where('on_live', '=', true)
            ->update(['on_live' => false]);
    }

    public function setOnLiveAndNumber(LiveProduct $liveProduct, int $number): void
    {
        $liveProduct->update([
            'on_live' => true,
            'number' => $number
        ]);
    }
}
