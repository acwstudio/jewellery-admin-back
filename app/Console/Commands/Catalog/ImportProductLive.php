<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ImportProductLive extends Command
{
    protected $signature = 'import:product_live';
    protected $description = 'Импортирует продукты Прямого эфира';

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
        $this->info('Live products importing...');

        $this->withoutTelescope(function () {
            $this->catalogModuleClient->importProductLive();
        });

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
