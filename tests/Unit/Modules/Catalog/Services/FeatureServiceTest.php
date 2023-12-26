<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Services;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Services\FeatureService;
use App\Modules\Catalog\Support\Blueprints\FeatureBlueprint;
use App\Modules\Catalog\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class FeatureServiceTest extends TestCase
{
    private FeatureService $featureService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->featureService = app(FeatureService::class);
    }

    public function testSuccessfulGet()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $results = $this->featureService->getFeature($feature->getKey());

        self::assertInstanceOf(Feature::class, $results);
    }

    public function testSuccessfulGetList()
    {
        Feature::factory(5)->create();

        $pagination = new Pagination(1, 4);
        $results = $this->featureService->getFeatures($pagination);

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertCount(4, $results->items());
    }

    public function testSuccessfulCreate()
    {
        $data = new FeatureBlueprint(
            FeatureTypeEnum::INSERT,
            'Фианит'
        );

        $results = $this->featureService->createFeature(
            $data
        );

        self::assertInstanceOf(Feature::class, $results);
    }

    public function testSuccessfulUpdate()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $data = new FeatureBlueprint(
            FeatureTypeEnum::INSERT,
            'Фианит'
        );

        $results = $this->featureService->updateFeature(
            $feature,
            $data
        );

        self::assertInstanceOf(Feature::class, $results);
        self::assertEquals(FeatureTypeEnum::INSERT, $results->type);
        self::assertEquals('Фианит', $results->value);
    }

    public function testSuccessfulDelete()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $this->featureService->deleteFeature($feature->getKey());

        self::assertModelMissing($feature);
    }

    public function testSuccessfulGenerateSlug()
    {
        $data = new FeatureBlueprint(
            FeatureTypeEnum::SHAPE,
            'Круг-1,25'
        );

        $results = $this->featureService->createFeature(
            $data
        );

        self::assertInstanceOf(Feature::class, $results);
        self::assertEquals($data->getType()->getSlug($data->getValue()), $results->slug);
        self::assertEquals('shape_krug_1_25', $results->slug);
    }
}
