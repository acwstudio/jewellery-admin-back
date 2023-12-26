<?php

declare(strict_types=1);

namespace App\Console\Commands\Orders;

use App\Console\Command;
use App\Packages\ModuleClients\OrdersModuleClientInterface;

class ImportOrderStatuses extends Command
{
    protected $signature = 'import:order:statuses';
    protected $description = 'Импортирует статусы заказов';

    public function __construct(
        protected OrdersModuleClientInterface $ordersModuleClient
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Order statuses importing...');

        $this->withoutTelescope(function () {
            $this->ordersModuleClient->importOrderStatuses();
        });

        $this->info("\nImport finished!");

        return Command::SUCCESS;
    }
}
