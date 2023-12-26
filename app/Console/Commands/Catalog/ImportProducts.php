<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ImportProducts extends Command
{
    protected $signature = 'import:products';
    protected $description = 'Импортирует продукты каталога';

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
        $this->info('Catalog products importing...');

        $this->withoutTelescope(function () {
            $this->catalogModuleClient->importProducts();
        });

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
