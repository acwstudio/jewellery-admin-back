<?php

declare(strict_types=1);

namespace App\Modules\Collections\Repository;

use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use App\Modules\Collections\Support\Blueprints\FavoriteBlueprint;
use App\Modules\Collections\Support\Filters\FavoriteFilter;
use App\Modules\Collections\Support\Pagination;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FavoriteRepository
{
    public function getById(int $id, bool $fail = false): ?Favorite
    {
        if ($fail) {
            return Favorite::findOrFail($id);
        }

        return Favorite::find($id);
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = Favorite::query()
            ->whereHas(
                'collection',
                fn (Builder $featureBuilder) => $featureBuilder
                ->where('is_active', '=', true)
            )
            ->orderBy('id', 'desc');

        $favorites = $query->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $favorites->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $favorites;
    }

    /**
     * @param FavoriteFilter $filter
     * @param bool $fail
     * @return Collection<Favorite>
     */
    public function getCollectionByFilter(
        FavoriteFilter $filter,
        bool $fail = false,
        ?bool $collectionIsActive = null
    ): Collection {
        $query = FilterQueryBuilder::fromQuery(Favorite::query())->withFilter($filter)->create();

        if (!empty($collectionIsActive)) {
            $query->whereHas(
                'collection',
                fn (Builder $featureBuilder) => $featureBuilder
                    ->where('is_active', '=', $collectionIsActive)
            );
        }

        /** @var Collection<Favorite> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function create(
        FavoriteBlueprint $favoriteBlueprintData,
        CollectionModel $collection,
        File $image,
        File $imageMob,
    ): Favorite {
        $favorite = new Favorite([
            'slug' => $favoriteBlueprintData->slug,
            'name' => $favoriteBlueprintData->name,
            'description' => $favoriteBlueprintData->description,
            'background_color' => $favoriteBlueprintData->background_color,
            'font_color' => $favoriteBlueprintData->font_color
        ]);

        $favorite->collection()->associate($collection);
        $favorite->image()->associate($image);
        $favorite->imageMob()->associate($imageMob);

        $favorite->save();

        return $favorite;
    }

    public function update(
        Favorite $favorite,
        FavoriteBlueprint $favoriteBlueprintData,
        CollectionModel $collection,
        File $image,
        File $imageMob,
    ): Favorite {
        $favorite->update([
            'slug' => $favoriteBlueprintData->slug,
            'name' => $favoriteBlueprintData->name,
            'description' => $favoriteBlueprintData->description,
            'background_color' => $favoriteBlueprintData->background_color,
            'font_color' => $favoriteBlueprintData->font_color
        ]);

        $favorite->collection()->associate($collection);
        $favorite->image()->associate($image);
        $favorite->imageMob()->associate($imageMob);

        $favorite->save();

        return $favorite;
    }

    public function delete(Favorite $favorite): void
    {
        $favorite->delete();
    }
}
