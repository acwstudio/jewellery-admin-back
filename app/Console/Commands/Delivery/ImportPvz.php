<?php

declare(strict_types=1);

namespace App\Console\Commands\Delivery;

use App\Console\Command;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;

class ImportPvz extends Command
{
    protected $signature = 'import:pvz';

    public function __construct(
        protected DeliveryModuleClientInterface $deliveryModuleClient
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->withoutTelescope(function () {
            $this->deliveryModuleClient->importPvz();
        });

        return self::SUCCESS;
    }
}
