<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\PaymentStatusChanged;
use App\Packages\ModuleClients\PaymentModuleClientInterface;

class PublishPaymentStatus1C
{
    public function __construct(
        private readonly PaymentModuleClientInterface $paymentModuleClient
    ) {
    }

    public function handle(PaymentStatusChanged $event): void
    {
        $this->paymentModuleClient->publishPaymentStatus($event->paymentId);
    }
}
