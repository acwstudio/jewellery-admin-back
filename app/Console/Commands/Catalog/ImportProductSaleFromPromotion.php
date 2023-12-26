<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ImportProductSaleFromPromotion extends Command
{
    protected $signature = 'import:product_sale:promotion {promotion_id}';
    protected $description = 'Импортирует продукты Акции';

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
        $this->info('Sale products from promotion importing...');

        $promotionId = $this->argument('promotion_id');
        $this->withoutTelescope(function () use ($promotionId) {
            $this->catalogModuleClient->importProductSaleFromPromotion((int)$promotionId);
        });

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
