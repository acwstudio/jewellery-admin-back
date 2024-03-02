<?php

declare(strict_types=1);

namespace Domain\Performance\CustomBuilders\Banner;

use Domain\Performance\Models\ImageBanner;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

final class SortBySequence implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

//        return $query->with(['imageBanners' => function ($query) use ($direction) {
//           dd($query->orderBy('image_banners.sequence', $direction)->toSql());
//        }]);
//        dd($query->whereHas('imageBanners')->orderBy('sequence')->get());
//        return $query->with(['imageBanners' => function ($query) use ($direction) {
////            dd($query->orderBy('image_banners.sequence', $direction)->get());
//            $query->orderBy('image_banners.sequence', $direction)->get();
//        }]);
//        dd($query->toSql());
//        dd($query->orderBy('image_banners.sequence', $direction)->toSql());
//        dd($query->with('image_banners')->orderBy('image_banners.sequence')->toSql());
//        dd($query->with('imageBanners')->get());

        $query->whereHas('imageBanners', function (Builder $query) use ($direction) {
            $query->orderBy('sequence', $direction);
        });
    }
}
