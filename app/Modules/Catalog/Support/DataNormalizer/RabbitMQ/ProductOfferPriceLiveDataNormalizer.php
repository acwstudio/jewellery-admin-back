<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ImportProductOfferPriceLiveData;
use App\Packages\Enums\ValueFormatEnum;
use App\Packages\Support\DataArray;
use Spatie\LaravelData\Data;

class ProductOfferPriceLiveDataNormalizer implements DataNormalizerInterface
{
    public function normalize($data): Data
    {
        $dataArray = new DataArray($data);

        return new ImportProductOfferPriceLiveData(
            $dataArray->get('UID', ValueFormatEnum::STRING_NOT_FORMAT),
            $dataArray->get('VendorCode', ValueFormatEnum::STRING_NOT_FORMAT),
            $dataArray->get('Date_time', ValueFormatEnum::DATETIME_TIMEZONE_SERVER),
            $dataArray->get('OnlinePrice', ValueFormatEnum::MONEY_DECIMAL),
            $dataArray->get('Size'),
            $data
        );
    }
}
