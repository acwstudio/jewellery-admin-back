<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Support\DataProvider\Monolith;

use App\Modules\Catalog\Support\DataProvider\Monolith\CategoryDataProvider;
use App\Packages\ModuleClients\MonolithModuleClientInterface;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @covers \App\Modules\Catalog\Support\DataProvider\Monolith\CategoryDataProvider
 */
class CategoryDataProviderTest extends TestCase
{
    protected MockInterface $monolithModuleClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->monolithModuleClient = $this->mock(MonolithModuleClientInterface::class);
    }

    /**
     * @dataProvider getCategoriesDataProvider
     */
    public function testGetCategories(array $categories, array $expectedCategories): void
    {
        $this->monolithModuleClient
            ->shouldReceive('getCategories')
            ->once()
            ->withNoArgs()
            ->andReturn($categories);

        /** @var CategoryDataProvider $categoryDataProvider */
        $categoryDataProvider = $this->app->make(CategoryDataProvider::class);
        $actualCategories = $categoryDataProvider->getRawData();

        $this->assertEquals($expectedCategories, $actualCategories);
    }

    protected function getMockCategories(): array
    {
        return [
            (object)[
                'name' => 'Кольца Title',
                'h1' => 'Кольца H1',
                'description' => 'Кольца Description',
                'meta_title' => 'Кольца Meta Title',
                'meta_description' => 'Кольца Meta Description',
                'meta_keywords' => 'Кольца,Украшения,Золото',
                'id' => '999999',
                'url' => 'zolotie_koltsa',
                'parent_id' => '888888',
            ],
        ];
    }

    protected function getExpectedCategories(): array
    {
        return [
            (object)[
                'name' => 'Кольца Title',
                'h1' => 'Кольца H1',
                'description' => 'Кольца Description',
                'meta_title' => 'Кольца Meta Title',
                'meta_description' => 'Кольца Meta Description',
                'meta_keywords' => 'Кольца,Украшения,Золото',
                'id' => '999999',
                'url' => 'zolotie_koltsa',
                'parent_id' => '888888',
            ],
        ];
    }

    protected function getCategoriesDataProvider(): array
    {
        return [
            [
                'categories' => $this->getMockCategories(),
                'expectedCategories' => $this->getExpectedCategories()],
            ];
    }
}
