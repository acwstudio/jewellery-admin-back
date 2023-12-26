<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\ApiClients\Payment\Responses\Callbacks\SberbankCallbackStatusData;
use App\Packages\DataObjects\Payment\AcquiringPaymentData;
use App\Packages\DataObjects\Payment\PaymentData;
use App\Packages\DataObjects\Payment\PaymentRequestData;

interface PaymentModuleClientInterface
{
    public function register(PaymentRequestData $paymentRequestData): AcquiringPaymentData;
    public function getOrderStatusExtended(mixed $id);
    public function webhookStatus(SberbankCallbackStatusData $data): void;
    public function publishPaymentStatus(int $id): void;
    public function getPayment(int $id): PaymentData;
}
