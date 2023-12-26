<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\DataNormalizer\Monolith;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Catalog\Product\MonolithProductFilterData;
use App\Packages\DataObjects\Catalog\ProductFeature\ImportProductFeatureData;
use App\Packages\Enums\ValueFormatEnum;
use App\Packages\Support\DataArray;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ProductFilterDataNormalizer implements DataNormalizerInterface
{
    private DataArray $dataArray;

    public function normalize($data): Data
    {
        $this->dataArray = new DataArray($data);

        return new MonolithProductFilterData(
            $this->dataArray->get('sku_id', ValueFormatEnum::STRING_NOT_FORMAT),
            $this->dataArray->get('name'),
            $this->dataArray->get('description'),
            $this->dataArray->get('is_active', ValueFormatEnum::BOOLEAN, false),
            $this->productFeatureNormalizer(),
            $this->dataArray->get('type', default: ''),
            $this->dataArray->get('filters.sub_type', ValueFormatEnum::ARRAY, [])
        );
    }

    private function productFeatureNormalizer(): Collection
    {
        $features = [];

        /** Повод */
        $this->setTypeProductFeatureData($features, 'filters.povod', FeatureTypeEnum::OCCASION);

        /** Стиль */
        $this->setTypeProductFeatureData($features, 'filters.style', FeatureTypeEnum::STYLE);

        /** Дизайн */
        $this->setTypeProductFeatureData($features, 'filters.design', FeatureTypeEnum::DESIGN);

        /** Металл */
        $this->setTypeProductFeatureData($features, 'filters.metal', FeatureTypeEnum::METAL);

        /** Цвет металла */
        $this->setTypeProductFeatureData($features, 'filters.metal_color', FeatureTypeEnum::METAL_COLOR);

        /** Вставка */
        $this->setTypeProductFeatureData($features, 'filters.insert', FeatureTypeEnum::INSERT);

        /** Цвет вставки */
        $this->setTypeProductFeatureData($features, 'filters.insert_color', FeatureTypeEnum::INSERT_COLOR);

        /** Пол */
        $this->setTypeProductFeatureData($features, 'filters.sex', FeatureTypeEnum::SEX);

        /** Форма огранки */
        $this->setTypeProductFeatureData($features, 'filters.shape', FeatureTypeEnum::SHAPE);

        return new Collection($features);
    }

    private function setTypeProductFeatureData(
        array &$features,
        string $fieldName,
        FeatureTypeEnum $type
    ): void {
        if (!$this->dataArray->has($fieldName)) {
            return;
        }

        $array = $this->dataArray->get($fieldName, ValueFormatEnum::ARRAY, []);

        foreach ($array as $item) {
            $features[] = new ImportProductFeatureData(
                type: $type,
                typeValue: ValueFormatEnum::STRING->format($item)
            );
        }
    }
}
