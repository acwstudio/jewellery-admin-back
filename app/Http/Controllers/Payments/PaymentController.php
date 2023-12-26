<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Packages\ApiClients\Payment\Responses\Callbacks\SberbankCallbackStatusData;
use App\Packages\ModuleClients\PaymentModuleClientInterface;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentModuleClientInterface $paymentModuleClient
    ) {
    }

    public function webhookStatus(SberbankCallbackStatusData $data): \Illuminate\Http\Response
    {
        $this->paymentModuleClient->webhookStatus($data);
        return \response('');
    }
}
