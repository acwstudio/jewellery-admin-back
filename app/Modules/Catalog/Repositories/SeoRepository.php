<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Seo;
use App\Modules\Catalog\Support\Filters\SeoFilter;
use App\Packages\DataObjects\Catalog\Seo\CreateSeoData;
use App\Packages\DataObjects\Catalog\Seo\UpdateSeoData;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeoRepository
{
    public function getById(int $id, bool $fail = false): ?Seo
    {
        if ($fail) {
            return Seo::findOrFail($id);
        }

        return Seo::find($id);
    }

    /**
     * @param SeoFilter $filter
     * @param bool $fail
     * @return Collection<Seo>
     */
    public function getList(SeoFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(Seo::query())->withFilter($filter)->create();

        /** @var Collection $seoList */
        $seoList = $query->get();

        if ($fail && $seoList->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $seoList;
    }

    public function create(CreateSeoData $createSeoData, Category $category, ?Seo $parent = null): Seo
    {
        $seo = new Seo([
            'url' => $createSeoData->url,
            'filters' => $createSeoData->filter->toArray(),
            'h1' => $createSeoData->h1,
            'meta_title' => $createSeoData->meta_title,
            'meta_description' => $createSeoData->meta_description
        ]);

        $seo->category()->associate($category);
        $seo->parent()->associate($parent);
        $seo->save();

        return $seo;
    }

    public function update(
        Seo $seo,
        UpdateSeoData $updateSeoData,
        Category $category,
        ?Seo $parent = null
    ): void {
        $data = [
            'filters' => $updateSeoData->filter->toArray(),
            'h1' => $updateSeoData->h1,
            'meta_title' => $updateSeoData->meta_title,
            'meta_description' => $updateSeoData->meta_description
        ];

        if (!empty($updateSeoData->url)) {
            $data['url'] = $updateSeoData->url;
        }

        $seo->update($data);

        $seo->category()->associate($category);
        $seo->parent()->associate($parent);
        $seo->save();
    }

    public function delete(Seo $seo): void
    {
        $seo->delete();
    }
}
