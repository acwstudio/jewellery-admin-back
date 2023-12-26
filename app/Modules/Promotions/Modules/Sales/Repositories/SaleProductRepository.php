<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Repositories;

use App\Modules\Promotions\Modules\Sales\Contracts\Pipelines\SaleProductQueryBuilderPipelineContract;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use App\Modules\Promotions\Modules\Sales\Support\Blueprints\SaleProductBlueprint;
use App\Modules\Promotions\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class SaleProductRepository
{
    public function getById(int $id, bool $fail = false): ?SaleProduct
    {
        if ($fail) {
            return SaleProduct::findOrFail($id);
        }
        return SaleProduct::find($id);
    }

    public function getByProductId(int $productId, bool $fail = false): ?SaleProduct
    {
        /** @var SaleProduct|null $model */
        $model = SaleProduct::query()->where('product_id', '=', $productId)
            ->get()
            ->first();

        if ($fail && null === $model) {
            throw (new ModelNotFoundException())->setModel(SaleProduct::class);
        }

        return $model;
    }

    public function getList(): Collection
    {
        $query = SaleProduct::query();

        /** @var SaleProductQueryBuilderPipelineContract $pipeline */
        $pipeline = app(SaleProductQueryBuilderPipelineContract::class);

        /** @var Collection $models */
        $models = $pipeline->send($query)->thenReturn()->get();
        return $models;
    }

    public function getListByPagination(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = SaleProduct::query();

        /** @var SaleProductQueryBuilderPipelineContract $pipeline */
        $pipeline = app(SaleProductQueryBuilderPipelineContract::class);

        /** @var LengthAwarePaginator $paginator */
        $paginator = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $paginator->total() === 0) {
            throw (new ModelNotFoundException())->setModel(SaleProduct::class);
        }

        return $paginator;
    }

    public function create(Sale $sale, SaleProductBlueprint $blueprint): SaleProduct
    {
        $model = new SaleProduct([
            'product_id' => $blueprint->product_id
        ]);

        $model->sale()->associate($sale);
        $model->save();

        return $model;
    }

    public function update(SaleProduct $model, Sale $sale): void
    {
        $model->sale()->associate($sale);
        $model->save();
    }

    public function createOrUpdate(Sale $sale, SaleProductBlueprint $blueprint): SaleProduct
    {
        $model = $this->getByProductId($blueprint->product_id);

        if (null === $model) {
            return $this->create($sale, $blueprint);
        }

        $this->update($model, $sale);
        return $model->refresh();
    }

    public function delete(SaleProduct $model): void
    {
        $model->delete();
    }
}
