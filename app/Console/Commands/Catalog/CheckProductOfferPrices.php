<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class CheckProductOfferPrices extends Command
{
    protected $signature = 'check:product_offer_prices';
    protected $description = 'Проверка цен торговых предложений';

    public function __construct(
        protected CatalogModuleClientInterface $catalogModuleClient
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Product offer prices checking...');

        $this->withoutTelescope(function () {
            $this->catalogModuleClient->checkProductOfferPrices();
        });

        $this->info("\nCheck finished!");

        return Command::SUCCESS;
    }
}
