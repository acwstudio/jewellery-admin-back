<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Catalog\ProductOffer\Stock\ImportProductOfferStockData;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\Enums\ValueFormatEnum;
use App\Packages\Support\DataArray;
use Spatie\LaravelData\Data;

class ProductOfferStockDataNormalizer implements DataNormalizerInterface
{
    public function normalize($data): Data
    {
        $dataArray = new DataArray($data);

        return new ImportProductOfferStockData(
            external_id: $dataArray->get('UID', ValueFormatEnum::STRING_NOT_FORMAT),
            sku: $dataArray->get('VendorCode', ValueFormatEnum::STRING_NOT_FORMAT),
            size: $dataArray->get('Size'),
            count: $dataArray->get('StockCount', ValueFormatEnum::INTEGER, 0),
            reason: OfferStockReasonEnum::NEW,
        );
    }
}
