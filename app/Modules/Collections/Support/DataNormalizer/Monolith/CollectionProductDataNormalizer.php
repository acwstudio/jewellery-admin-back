<?php

declare(strict_types=1);

namespace App\Modules\Collections\Support\DataNormalizer\Monolith;

use App\Modules\Collections\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Collections\CollectionProduct\ImportCollectionProductData;
use App\Packages\Enums\ValueFormatEnum;
use App\Packages\Support\DataArray;
use Spatie\LaravelData\Data;

class CollectionProductDataNormalizer implements DataNormalizerInterface
{
    public function normalize(array $data): Data
    {
        $dataArray = new DataArray($data);

        return new ImportCollectionProductData(
            $dataArray->get('collection_id', ValueFormatEnum::INTEGER),
            $dataArray->get('product_ids', ValueFormatEnum::ARRAY, []),
            $dataArray->get('category_ids', ValueFormatEnum::ARRAY, [])
        );
    }
}
