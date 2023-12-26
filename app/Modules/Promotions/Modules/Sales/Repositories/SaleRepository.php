<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Repositories;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Support\Blueprints\SaleBlueprint;
use App\Modules\Promotions\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaleRepository
{
    public function getById(int $id, bool $fail = false): ?Sale
    {
        if ($fail) {
            return Sale::findOrFail($id);
        }
        return Sale::find($id);
    }

    public function getBySlug(string $slug, bool $fail = false): ?Sale
    {
        /** @var Sale|null $model */
        $model = Sale::query()->where('slug', '=', $slug)
            ->get()
            ->first();

        if ($fail && null === $model) {
            throw (new ModelNotFoundException())->setModel(Sale::class);
        }

        return $model;
    }

    public function getByPromotionId(int $promotionId, bool $fail = false): ?Sale
    {
        /** @var Sale|null $model */
        $model = Sale::query()->where('promotion_id', '=', $promotionId)
            ->get()
            ->first();

        if ($fail && null === $model) {
            throw (new ModelNotFoundException())->setModel(Sale::class);
        }

        return $model;
    }

    public function getListByPagination(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $paginator = Sale::query()
            ->whereHas(
                'promotion',
                fn (Builder $promotionBuilder) => $promotionBuilder
                    ->where('is_active', '=', true)
            )
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $paginator->total() === 0) {
            throw (new ModelNotFoundException())->setModel(Sale::class);
        }

        return $paginator;
    }

    public function create(Promotion $promotion, SaleBlueprint $blueprint): Sale
    {
        $model = new Sale([
            'title' => $blueprint->title,
            'slug' => $blueprint->slug
        ]);

        $model->promotion()->associate($promotion);
        $model->save();

        return $model;
    }

    public function update(Sale $model, SaleBlueprint $blueprint): void
    {
        $model->update([
            'title' => $blueprint->title,
            'slug' => $blueprint->slug
        ]);
    }

    public function delete(Sale $model): void
    {
        $model->delete();
    }
}
