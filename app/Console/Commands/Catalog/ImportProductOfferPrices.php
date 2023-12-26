<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ImportProductOfferPrices extends Command
{
    protected $signature = 'import:product_offer_prices {type}';
    protected $description = 'Импортирует продуктовые цены';

    public function __construct(
        protected CatalogModuleClientInterface $catalogModuleClient
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $type = $this->argument('type');
        $typeEnum = OfferPriceTypeEnum::from($type);

        $this->info('Product offer prices importing...');

        $this->withoutTelescope(function () use ($typeEnum) {
            $this->catalogModuleClient->importProductOfferPrices($typeEnum);
        });

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
