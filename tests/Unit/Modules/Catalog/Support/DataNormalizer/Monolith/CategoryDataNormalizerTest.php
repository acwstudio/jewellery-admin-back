<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support\DataNormalizer\Monolith;

use App\Modules\Catalog\Support\DataNormalizer\Monolith\CategoryDataNormalizer;
use App\Packages\DataObjects\Catalog\Category\ImportCategoryData;
use Tests\TestCase;

/**
 * @covers \App\Modules\Catalog\Support\DataNormalizer\Monolith\CategoryDataNormalizer
 */
class CategoryDataNormalizerTest extends TestCase
{
    /**
     * @dataProvider normalizeDataProvider
     * @return void
     */
    public function testNormalize(ImportCategoryData $expectedImportCategoryData, object $data)
    {
        $categoryDataNormalizer = new CategoryDataNormalizer();

        $importCategoryData = $categoryDataNormalizer->normalize($data);

        $this->assertEquals($expectedImportCategoryData, $importCategoryData);
    }

    public function getExpectedImportCategoryData(): ImportCategoryData
    {
        return new ImportCategoryData(
            'Кольца Title',
            'Кольца H1',
            'Кольца Description',
            'Кольца Meta Title',
            'Кольца Meta Description',
            'Кольца,Украшения,Золото',
            '888888',
            '999999',
            'zolotie_koltsa',
        );
    }

    public function normalizeDataProvider(): array
    {
        return [
            [
                'expectedImportCategoryData' => $this->getExpectedImportCategoryData(),
                'data' => (object)[
                    'name' => 'Кольца Title',
                    'h1' => 'Кольца H1',
                    'description' => 'Кольца Description',
                    'meta_title' => 'Кольца Meta Title',
                    'meta_description' => 'Кольца Meta Description',
                    'meta_keywords' => 'Кольца,Украшения,Золото',
                    'id' => '999999',
                    'url' => 'zolotie_koltsa',
                    'parent_id' => '888888',
                ]
            ],
        ];
    }
}
