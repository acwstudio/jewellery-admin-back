<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

use App\Packages\DataObjects\Payment\PaymentRequestData;
use App\Packages\Exceptions\Sber\HttpClientException;
use App\Packages\Exceptions\Sber\NetworkException;
use App\Packages\ModuleClients\ApiSberClientInterface;

class SberService
{
    public function __construct(
        private readonly ApiSberClientInterface $client,
    ) {
    }

    /**
     * @throws HttpClientException
     * @throws NetworkException
     */
    public function register(PaymentRequestData $paymentRequestData): array
    {
        return $this->client->registerOrder($paymentRequestData);
    }

    /**
     * @throws NetworkException
     * @throws HttpClientException
     */
    public function getOrderStatusExtended($id): array
    {
        return $this->client->getOrderStatusExtended($id);
    }
}
