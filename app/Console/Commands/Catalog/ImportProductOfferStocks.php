<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ImportProductOfferStocks extends Command
{
    protected $signature = 'import:product_offer_stocks';
    protected $description = 'Импортирует продуктовые остатки';

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
        $this->info('Product offer stocks importing...');

        $this->withoutTelescope(function () {
            $this->catalogModuleClient->importProductOfferStocks();
        });

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
