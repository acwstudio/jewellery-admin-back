<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\PreviewImage;

use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Storage\Models\Media;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PreviewImageControllerDeleteTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/preview_image/';
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
        $previewImage = $this->getPreviewImage();

        $response = $this->delete(self::METHOD . $previewImage->id);
        $response->assertSuccessful();
    }

    public function testSuccessfulByPreviewImage()
    {
        $previewImage = $this->getPreviewImage();

        /** @var Product $product */
        $product = Product::factory()->create(['preview_image_id' => $previewImage->id]);
        self::assertInstanceOf(PreviewImage::class, $product->previewImage);

        $response = $this->delete(self::METHOD . $previewImage->id);
        $response->assertSuccessful();

        $product->refresh();
        self::assertEmpty($product->previewImage);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 5);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        $previewImage = $this->getPreviewImage();

        $response = $this->delete(self::METHOD . $previewImage->id);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function getPreviewImage(): PreviewImage
    {
        /** @var PreviewImage $previewImage */
        $previewImage = PreviewImage::factory()->create();
        Media::factory()->create([
            'model_type' => PreviewImage::class,
            'model_id' => $previewImage->getKey()
        ]);

        return $previewImage;
    }
}
