<?php

declare(strict_types=1);

namespace App\Console\Commands\Catalog;

use App\Console\Command;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class GenerateProductSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:product:slug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация слагов продуктов каталога';

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

        $this->info('Products slug generating...');

        $this->withoutTelescope(function () use ($bar) {
            $this->catalogModuleClient->generateProductSlugs([$bar, 'advance']);
        });

        $bar->finish();

        $this->info("\nGenerate finished!");

        return Command::SUCCESS;
    }
}
