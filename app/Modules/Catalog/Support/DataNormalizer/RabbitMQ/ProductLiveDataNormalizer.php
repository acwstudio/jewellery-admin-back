<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ImportProductLiveData;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ProductLiveData;
use App\Packages\DataObjects\Catalog\Product\Import\ProductLive\ProductLivePriceData;
use App\Packages\Enums\ValueFormatEnum;
use App\Packages\Support\DataArray;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ProductLiveDataNormalizer implements DataNormalizerInterface
{
    public function normalize($data): Data
    {
        return new ImportProductLiveData(
            $this->createProductLiveDataCollection($data)
        );
    }

    private function createProductLiveDataCollection(array $data): Collection
    {
        $collection = new Collection();
        foreach ($data as $item) {
            $itemData = new DataArray($item);

            $prices = $this->createProductLivePriceDataCollection(
                $itemData->get('Prices', ValueFormatEnum::ARRAY, [])
            );

            $productLiveData = new ProductLiveData(
                $itemData->get('Art', ValueFormatEnum::STRING_NOT_FORMAT),
                $itemData->get('Slot', ValueFormatEnum::INTEGER, 0),
                $itemData->get('Date_time', ValueFormatEnum::DATETIME_TIMEZONE_SERVER),
                $prices
            );

            $collection->add($productLiveData);
        }

        return $collection;
    }

    private function createProductLivePriceDataCollection(array $data): Collection
    {
        $collection = new Collection();
        foreach ($data as $item) {
            $itemData = new DataArray($item);

            $liveProductData = new ProductLivePriceData(
                $itemData->get('Size'),
                $itemData->get('OnlinePrice', ValueFormatEnum::MONEY_DECIMAL)
            );

            $collection->add($liveProductData);
        }

        return $collection;
    }
}
