<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\DataNormalizer\Monolith;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Catalog\Category\ImportCategoryData;
use Spatie\LaravelData\Data;

class CategoryDataNormalizer implements DataNormalizerInterface
{
    public function normalize($data): Data
    {
        return new ImportCategoryData(
            $data->name,
            $data->h1,
            $data->description,
            $data->meta_title,
            $data->meta_description,
            $data->meta_keywords,
            (string)$data->parent_id,
            (string)$data->id,
            $data->url
        );
    }
}
