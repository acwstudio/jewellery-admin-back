<?php

declare(strict_types=1);

namespace Tests\Unit\Packages\ModuleClients;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Support\DataProvider\DataProviderInterface;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class CatalogModuleClientTest extends TestCase
{
    /**
     * @dataProvider newCategoryProvider
     */
    public function testImportCategories(
        array $categories,
        ?array $parentAttributes = null,
        ?array $childAttributes = null,
        ?array $expectedParentAttributes = null,
        ?array $expectedChildAttributes = null,
        ?int $expectedCount = null
    ): void {
        /** @var Category $parent */
        $parent = Category::factory()->create($parentAttributes);

        /** @var Category $child */
        $child = Category::factory()
            ->for($parent, 'parent')
            ->create($childAttributes);

        $this
            ->mock(DataProviderInterface::class)
            ->shouldReceive('getRawData')
            ->andReturn($categories);

        /** @var CatalogModuleClientInterface $catalogModuleClient */
        $catalogModuleClient = App::make(CatalogModuleClientInterface::class);

        $catalogModuleClient->importCategories();

        if (null !== $expectedCount) {
            $this->assertCount($expectedCount, Category::all());
        }

        if (null !== $expectedParentAttributes) {
            $parent->refresh();

            $this->assertEquals(
                $expectedParentAttributes,
                array_intersect_key($parent->getAttributes(), $expectedParentAttributes)
            );
        }

        if (null !== $expectedChildAttributes) {
            $child->refresh();

            $this->assertEquals(
                $expectedChildAttributes,
                array_intersect_key($child->getAttributes(), $expectedChildAttributes)
            );
        }
    }

    public static function newCategoryProvider(): array
    {
        return [
            [
                'categories' => [
                    (object)[
                        'name' => fake()->text(50),
                        'h1' => fake()->text(50),
                        'description' => fake()->text,
                        'meta_title' => fake()->text(50),
                        'meta_description' => fake()->text(50),
                        'meta_keywords' => fake()->text(50),
                        'id' => 999999,
                        'url' => fake()->uuid,
                        'parent_id' => null,
                    ]
                ],
                'parentAttributes' => [
                    'external_id' => '888888'
                ],
                'childAttributes' => [
                    'external_id' => '777777'
                ],
                'expectedParentAttributes' => null,
                'expectedChildAttributes' => null,
                'expectedCount' => 3,
            ]
        ];
    }
}
