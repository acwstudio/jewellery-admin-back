<?php

declare(strict_types=1);

namespace App\Console\Commands\Collections;

use App\Console\Command;
use App\Modules\Collections\Support\Filters\CollectionFilter;
use App\Packages\ModuleClients\CollectionModuleClientInterface;

class ExportCollections extends Command
{
    protected $signature = 'export:collections {ids?}';
    protected $description = 'Экспортирует коллекций';

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
        $ids = $this->argument('ids');

        if (null !== $ids) {
            $ids = collect(explode(',', $ids));
        }

        $filter = new CollectionFilter(
            id: $ids
        );

        $this->info('Collections exporting...');

        $this->withoutTelescope(function () use ($filter) {
            $this->collectionModuleClient->exportCollections($filter);
        });

        $this->info("\nExport finished!");

        return Command::SUCCESS;
    }
}
