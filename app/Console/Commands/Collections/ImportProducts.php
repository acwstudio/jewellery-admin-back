<?php

declare(strict_types=1);

namespace App\Console\Commands\Collections;

use App\Console\Command;
use App\Packages\ModuleClients\CollectionModuleClientInterface;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:collection_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импортирует продукты коллекций';

    public function __construct(
        protected CollectionModuleClientInterface $collectionModuleClient
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

        $this->info('Collection products importing...');

        $this->withoutTelescope(function () use ($bar) {
            $this->collectionModuleClient->importCollectionProducts([$bar, 'advance']);
        });

        $bar->finish();

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
