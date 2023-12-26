<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\PreviewImage;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Storage\Models\Media;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PreviewImageControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/preview_image';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Storage::fake();
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        $this->createPreviewImage(3);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulPagination()
    {
        $this->createPreviewImage(5);

        $response = $this->get(self::METHOD . '?pagination[page]=1&pagination[per_page]=3');
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulEmptyItems()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertEmpty($content['items']);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        $this->createPreviewImage();

        $response = $this->get(self::METHOD);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function createPreviewImage(int $count = 1): void
    {
        $previewImages = PreviewImage::factory($count)->create();
        foreach ($previewImages as $previewImage) {
            Media::factory()->create([
                'model_type' => PreviewImage::class,
                'model_id' => $previewImage->getKey()
            ]);

            $previewImage->refresh();
        }
    }
}
