<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderHasImageFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        $hasImage = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($hasImage)) {
            throw new \Exception('The has_image parameter must be boolean.');
        }

        if ($hasImage) {
            $this->filterHasImage($query);
        } else {
            $this->filterNotHasImage($query);
        }

        return $query;
    }

    private function filterHasImage(Builder $query): void
    {
        $query->where(
            fn (Builder $builder) => $builder
                ->whereHas(
                    'imageUrls',
                    fn (Builder $imageUrlsBuilder) => $imageUrlsBuilder
                        ->where('is_main', '=', true)
                )
                ->orWhereNotNull('preview_image_id')
        );
    }

    private function filterNotHasImage(Builder $query): void
    {
        $query
            ->whereDoesntHave(
                'imageUrls',
                fn (Builder $imageUrlsBuilder) => $imageUrlsBuilder
                    ->where('is_main', '=', true)
            )
            ->whereNull(['preview_image_id']);
    }
}
