<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use App\Modules\Collections\Models\Stone;
use App\Modules\Storage\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

abstract class CollectionTestCase extends TestCase
{
    public function createCollections(int $count): Collection
    {
        /** @var File $file */
        $file = $this->createFiles(1)->first();
        $stones = Stone::factory(4)->create();
        $categories = Category::factory(3)->create();
        $products = Product::factory(3)->afterCreating(
        /** @phpstan-ignore-next-line */
            fn (Product $product) => $product->categories()->attach($categories->random(1))
        )->create(['setFull' => true]);

        $collections = CollectionModel::factory($count)->create();
        /** @var CollectionModel $collection */
        foreach ($collections as $collection) {
            $collection->previewImage()->associate($file);
            $collection->previewImageMob()->associate($file);
            $collection->bannerImage()->associate($file);
            $collection->bannerImageMob()->associate($file);

            $collection->products()->attach($products);
            $collection->stones()->attach($stones);
            $collection->categories()->attach($categories);

            $collection->save();
        }

        return $collections;
    }

    public function createFiles(int $count): Collection
    {
        $files = File::factory($count)->create();

        /** @var File $file */
        foreach ($files as $file) {
            Media::factory()->create(['model_type' => File::class, 'model_id' => $file->getKey()]);
        }

        return $files;
    }

    public function createFavorites(int $count): Collection
    {
        /** @var File $file */
        $file = $this->createFiles(1)->first();

        /** @var CollectionModel $collection */
        $collection = $this->createCollections(1)->first();

        $favorites = Favorite::factory($count)->create();
        /** @var Favorite $favorite */
        foreach ($favorites as $favorite) {
            $favorite->collection()->associate($collection);
            $favorite->image()->associate($file);
            $favorite->imageMob()->associate($file);

            $favorite->save();
        }

        return $favorites;
    }
}
