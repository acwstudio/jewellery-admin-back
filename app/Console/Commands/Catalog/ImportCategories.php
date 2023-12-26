<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class ImportCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:catalog_categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импортирует категории каталога';

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

        $this->info('Catalog categories importing...');

        $this->withoutTelescope(function () use ($bar) {
            $this->catalogModuleClient->importCategories([$bar, 'advance']);
        });

        $bar->finish();

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
