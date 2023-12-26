<?php

declare(strict_types=1);

namespace App\Console\Commands\Collections;

use App\Console\Command;
use App\Packages\ModuleClients\CollectionModuleClientInterface;

class ImportCollections extends Command
{
    protected $signature = 'import:collections';
    protected $description = 'Импортирует коллекций';

    public function __construct(
        protected CollectionModuleClientInterface $collectionModuleClient
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Collections importing...');

        $this->withoutTelescope(function () {
            $this->collectionModuleClient->importCollections();
        });

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
