<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Services;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Services\CatalogProductFilterService;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterListData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductOptionValueData;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;
use Tests\TestCase;

class CatalogProductFilterServiceTest extends TestCase
{
    private CatalogProductFilterService $catalogProductFilterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->catalogProductFilterService = app(CatalogProductFilterService::class);
    }

    public function testSuccessfulGetList()
    {
        $data = new GetListProductFilterData();
        $result = $this->catalogProductFilterService->getList($data);
        self::assertInstanceOf(CatalogProductFilterListData::class, $result);
    }

    public function testSuccessfulGetListStatic()
    {
        $result = $this->catalogProductFilterService->getListStatic();
        self::assertInstanceOf(CatalogProductFilterListData::class, $result);
    }

    public function testSuccessfulGetListBySize()
    {
        $data = new GetListProductFilterData();
        ProductOffer::factory()->create(['size' => '16']);
        ProductOffer::factory()->create(['size' => '12.5']);
        ProductOffer::factory()->create(['size' => '19']);
        ProductOffer::factory()->create(['size' => '11']);
        ProductOffer::factory()->create(['size' => '22-25']);
        ProductOffer::factory()->create(['size' => '8-13']);

        $result = $this->catalogProductFilterService->getList($data);
        self::assertInstanceOf(CatalogProductFilterListData::class, $result);
    }

    public function testSuccessfulGetListByFeatureMetal()
    {
        $data = new GetListProductFilterData();
        $this->createFeature(FeatureTypeEnum::METAL, 'Серебро', 3);
        $this->createFeature(FeatureTypeEnum::METAL, 'Золото', 1);
        $this->createFeature(FeatureTypeEnum::METAL, 'Бронза', 2);
        $this->createFeature(FeatureTypeEnum::METAL, 'Неизвестно');

        $result = $this->catalogProductFilterService->getList($data);
        self::assertInstanceOf(CatalogProductFilterListData::class, $result);
        /** @var CatalogProductFilterData $filter */
        foreach ($result->filters as $filter) {
            if ('feature[metal]' === $filter->name) {
                self::assertNotEmpty($filter->settings->options);
                $options = $filter->settings->options->toArray();
                self::assertEquals('Золото', $options[0]['name']);
                self::assertEquals('Бронза', $options[1]['name']);
                self::assertEquals('Серебро', $options[2]['name']);
                self::assertEquals('Неизвестно', $options[3]['name']);
            }
        }
    }

    private function createFeature(FeatureTypeEnum $type, string $value, ?int $position = null): void
    {
        $feature = Feature::factory()->create([
            'type' => $type,
            'value' => $value,
            'slug' => $type->getSlug($value),
            'position' => $position
        ]);
        ProductFeature::factory()->create(['feature_id' => $feature]);
    }
}
