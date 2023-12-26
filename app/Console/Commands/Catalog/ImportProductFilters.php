<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ImportProductFilters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:catalog_product_filters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импортирует фильтры продуктов каталога';

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
        $bar = $this->initializeProgressBar();

        $this->info('Catalog product filters importing...');

        $this->withoutTelescope(function () use ($bar) {
            $this->catalogModuleClient->importProductFilters([$bar, 'advance']);
        });

        $bar->finish();

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
