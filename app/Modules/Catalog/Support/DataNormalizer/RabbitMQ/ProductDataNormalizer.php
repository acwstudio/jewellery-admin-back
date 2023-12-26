<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\DataNormalizer\RabbitMQ;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Catalog\Product\ImageUrl\ImportProductImageUrlData;
use App\Packages\DataObjects\Catalog\Product\ImportProductData;
use App\Packages\DataObjects\Catalog\Product\VideoUrl\ImportProductVideoUrlData;
use App\Packages\DataObjects\Catalog\ProductFeature\ImportProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductOffer\ImportProductOfferData;
use App\Packages\Enums\ValueFormatEnum;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ProductDataNormalizer implements DataNormalizerInterface
{
    public function normalize($data): Data
    {
        return new ImportProductData(
            external_id: $this->getField($data, 'UID', ValueFormatEnum::STRING_NOT_FORMAT),
            sku: $this->getField($data, 'VendorCode', ValueFormatEnum::STRING_NOT_FORMAT),
            name: $this->getField($data, 'Name', default: ''),
            description: $this->getField($data, 'Description', default: ''),
            productFeatures: $this->productFeatureNormalizer($data),
            productOffers: $this->productOfferNormalizer($data),
            productImageUrls: $this->productImageUrlNormalizer($data),
            productVideoUrls: $this->productVideoUrlNormalizer($data),
            productSubCategories: $this->productSubCategoriesNormalizer($data),
            is_active: false,
            productCategory: $this->getField($data, 'ProductType'),
            siteName: $this->getField($data, 'SiteName')
        );
    }

    private function productSubCategoriesNormalizer(array $data): Collection
    {
        $categories = collect();

        $subCategories = $this->getField($data, 'ProductSubTypes', ValueFormatEnum::ARRAY, []);
        foreach ($subCategories as $subCategory) {
            $categories->add($subCategory);
        }

        return $categories;
    }

    private function productFeatureNormalizer(array $data): Collection
    {
        $productFeatures = [];

        /** Поставщик */
        $this->setTypeProductFeatureData($productFeatures, $data, 'Provider', FeatureTypeEnum::PROVIDER);

        /** Проба */
        $this->setTypeProductFeatureData($productFeatures, $data, 'Fineness', FeatureTypeEnum::PROBE);

        /** Средний вес */
        $this->setDynamicProductFeatureData($productFeatures, $data, 'AvgWeight', 'Средний вес');

        /** Покрытие */
        $this->setTypeProductFeatureData($productFeatures, $data, 'UVI_Coating', FeatureTypeEnum::COATING);

        /** Новинка */
        $this->setBooleanProductFeatureData($productFeatures, $data, 'IsNew', 'Новинка');

        /** Люкс */
        $this->setBooleanProductFeatureData($productFeatures, $data, 'Luxe', 'Люкс');

        /** Хит продаж */
        $this->setBooleanProductFeatureData($productFeatures, $data, 'IsBestseller', 'Хит продаж');

        /** Размер изделия */
        $this->setDynamicProductFeatureData($productFeatures, $data, 'UVI_LengthHeight', 'Размер изделия');

        /** Плетение */
        $this->setTypeProductFeatureData($productFeatures, $data, 'UVI_Weaving', FeatureTypeEnum::WEAVING);

        /** Размеры камня */
        $this->setDynamicProductFeatureData($productFeatures, $data, 'UVI_StoneSize', 'Размеры камня');

        /** Средний вес металла */
        $this->setDynamicProductFeatureData($productFeatures, $data, 'UVI_MetalAverageWeight', 'Средний вес металла');

        /** Средний вес вставок */
        $this->setDynamicProductFeatureData($productFeatures, $data, 'UVI_InsertsAverageWeight', 'Средний вес вставок');

        /** Толщина цепи браслета */
        $this->setDynamicProductFeatureData($productFeatures, $data, 'UVI_BraceletChainThick', 'Толщина цепи браслета');

        /** Ширина шинки кольца */
        $this->setDynamicProductFeatureData($productFeatures, $data, 'UVI_RingShangWidth', 'Ширина шинки кольца');

        /** Коллекция */
        $this->setTypeProductFeatureData($productFeatures, $data, 'UVI_Collection', FeatureTypeEnum::COLLECTION);

        /** Дизайны */
        $this->setTypeProductFeatureDataByArray($productFeatures, $data, 'ProductDesigns', FeatureTypeEnum::DESIGN);
        /** Стили */
        $this->setTypeProductFeatureDataByArray($productFeatures, $data, 'ProductStyles', FeatureTypeEnum::STYLE);
        /** Поводы */
        $this->setTypeProductFeatureDataByArray($productFeatures, $data, 'ProductOccasions', FeatureTypeEnum::OCCASION);

        /** !!! Временно отключено !!! */
        $this->commonFeature($productFeatures, $data, true);

        return new Collection($productFeatures);
    }

    /** !!! НЕ УДАЛЯТЬ! Временно отключили импорт данных свойств !!! */
    private function commonFeature(array &$productFeatures, array $data, bool $disable = false): void
    {
        if ($disable) {
            return;
        }

        /** Основной металл */
        $this->setMainMetalProductFeatureData($productFeatures, $data);

        /** Основная вставка */
        $this->setTypeProductFeatureData(
            $productFeatures,
            $data,
            'Insert',
            FeatureTypeEnum::INSERT,
            true
        );

        /** Вставки */
        $this->setInsertProductFeatureData($productFeatures, $data['Inserts'] ?? []);

        /** Металлы */
        $this->setMaterialProductFeatureData($productFeatures, $data['Materials'] ?? []);
    }

    private function productOfferNormalizer(array $data): Collection
    {
        if (empty($data['trade_offers'])) {
            return new Collection();
        }

        $productOffers = [];

        foreach ($data['trade_offers'] as $offer) {
            $productOffers[] = new ImportProductOfferData(
                $this->getField($offer, 'Size'),
                $this->getField($offer, 'Weight')
            );
        }

        return new Collection($productOffers);
    }

    private function productImageUrlNormalizer(array $data): Collection
    {
        if (empty($data['Photo'])) {
            return new Collection();
        }

        $productImageUrls = [];

        foreach ($data['Photo'] as $key => $image) {
            $path = $this->getField($image, 'Bucket', ValueFormatEnum::STRING_NOT_FORMAT) .
                '/' . $this->getField($image, 'Key', ValueFormatEnum::STRING_NOT_FORMAT);
            $productImageUrls[] = new ImportProductImageUrlData(
                $path,
                $key === 0
            );
        }

        return new Collection($productImageUrls);
    }

    private function productVideoUrlNormalizer(array $data): Collection
    {
        $collection = new Collection();
        if (empty($data['Video'])) {
            return $collection;
        }

        foreach ($data['Video'] as $video) {
            $path = $this->getField($video, 'Bucket', ValueFormatEnum::STRING_NOT_FORMAT) .
                '/' . $this->getField($video, 'Key', ValueFormatEnum::STRING_NOT_FORMAT);
            $collection->add(
                new ImportProductVideoUrlData($path)
            );
        }

        return $collection;
    }

    private function setMainMetalProductFeatureData(array &$productFeatures, array $data): void
    {
        if (!empty($data['Metal'])) {
            $children = [];

            /** Цвет металла */
            $this->setTypeProductFeatureData(
                $children,
                $data,
                'MetalColor',
                FeatureTypeEnum::METAL_COLOR
            );

            $productFeatures[] = new ImportProductFeatureData(
                type: FeatureTypeEnum::METAL,
                typeValue: $this->getField($data, 'Metal'),
                children: new Collection($children)
            );
        }
    }

    private function setInsertProductFeatureData(array &$productFeatures, array $inserts): void
    {
        foreach ($inserts as $insert) {
            $children = [];

            /** Вес */
            $this->setDynamicProductFeatureData($children, $insert, 'InsertWeight', 'Вес');

            /** Количество */
            $this->setDynamicProductFeatureData($children, $insert, 'InsertCount', 'Количество');

            /** Форма огранки */
            $this->setTypeProductFeatureData($children, $insert, 'InsertShape', FeatureTypeEnum::SHAPE);

            /** Цвет */
            $this->setTypeProductFeatureData($children, $insert, 'Color', FeatureTypeEnum::INSERT_COLOR);

            $productFeatures[] = new ImportProductFeatureData(
                type: FeatureTypeEnum::INSERT,
                typeValue: $this->getField($insert, 'Insert'),
                children: new Collection($children)
            );
        }
    }

    private function setMaterialProductFeatureData(array &$productFeatures, array $materials): void
    {
        foreach ($materials as $material) {
            $childrenProductFeature = [];

            if (!empty($material['Fineness'])) {
                $childrenProductFeature[] = new ImportProductFeatureData(
                    FeatureTypeEnum::PROBE,
                    $this->getField($material, 'Fineness')
                );
            }

            $productFeatures[] = new ImportProductFeatureData(
                type: FeatureTypeEnum::METAL,
                typeValue: $this->getField($material, 'Metal'),
                children: new Collection($childrenProductFeature)
            );
        }
    }

    private function setTypeProductFeatureData(
        array &$productFeatures,
        array $data,
        string $fieldName,
        FeatureTypeEnum $type,
        ?bool $is_main = null
    ): void {
        if (!empty($data[$fieldName])) {
            $productFeatures[] = new ImportProductFeatureData(
                type: $type,
                typeValue: $this->getField($data, $fieldName),
                is_main: $is_main
            );
        }
    }

    private function setTypeProductFeatureDataByArray(
        array &$productFeatures,
        array $data,
        string $fieldName,
        FeatureTypeEnum $type,
        ?bool $is_main = null
    ): void {
        if (!empty($data[$fieldName]) && is_array($data[$fieldName])) {
            foreach ($data[$fieldName] as $value) {
                $productFeatures[] = new ImportProductFeatureData(
                    type: $type,
                    typeValue: $value,
                    is_main: $is_main
                );
            }
        }
    }

    private function setDynamicProductFeatureData(
        array &$productFeatures,
        array $data,
        string $fieldName,
        string $typeValue
    ): void {
        if (!empty($data[$fieldName])) {
            $productFeatures[] = new ImportProductFeatureData(
                type: FeatureTypeEnum::DYNAMIC,
                typeValue: $typeValue,
                value: $this->getField($data, $fieldName)
            );
        }
    }

    private function setBooleanProductFeatureData(
        array &$productFeatures,
        array $data,
        string $fieldName,
        string $typeValue
    ): void {
        if (!empty($data[$fieldName])) {
            $productFeatures[] = new ImportProductFeatureData(
                type: FeatureTypeEnum::BOOLEAN,
                typeValue: $typeValue,
            );
        }
    }

    private function getField(
        array $data,
        string $name,
        ValueFormatEnum $formatType = ValueFormatEnum::STRING,
        $default = null
    ) {
        $formatEmpty = [ValueFormatEnum::STRING->value];
        $value = $data[$name] ?? null;
        if (in_array($formatType->value, $formatEmpty) && empty($value)) {
            return $default;
        } elseif (null === $value) {
            return $default;
        }

        return $formatType->format($value);
    }
}
