<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Modules\Catalog\Models\Brand;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Modules\Catalog\Models\ProductOfferStock;

class ScoutImport extends Command
{
    protected $signature = 'scout:import:catalog';
    protected $description = 'Импортирует модели каталога в поисковый индекс';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Scout importing catalog...');

        $this->scoutImport();

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }

    private function scoutImport(): void
    {
        foreach ($this->getModels() as $model) {
            $this->call('scout:import', ['model' => $model]);
            $this->info("\nScout imported model: {$model}");
        }
    }

    private function getModels(): array
    {
        return [
            Brand::class,
            Category::class,
            Feature::class,
            PreviewImage::class,
            Product::class,
            ProductFeature::class,
            ProductImageUrl::class,
            ProductOffer::class,
            ProductOfferPrice::class,
            ProductOfferStock::class,
            ProductOfferReservation::class
        ];
    }
}
