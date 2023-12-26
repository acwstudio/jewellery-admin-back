<?php

declare(strict_types=1);

namespace App\Modules\Payment\UseCases;

use App\Modules\Payment\Services\PaymentService;
use App\Modules\Payment\Services\SberService;
use App\Modules\Payment\Traits\HasConfig;
use App\Packages\DataObjects\Payment\AcquiringPaymentData;
use App\Packages\DataObjects\Payment\PaymentRequestData;
use App\Packages\Exceptions\Sber\ConfigException;
use App\Packages\Exceptions\Sber\HttpClientException;
use App\Packages\Exceptions\Sber\NetworkException;
use App\Packages\Exceptions\Sber\ResponseProcessingException;
use Throwable;

class RegisterOrder
{
    use HasConfig;

    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly SberService $sberService,
    ) {
    }

    /**
     * Регистрация заказа
     *
     * @param  PaymentRequestData  $paymentRequestData
     *
     * @return AcquiringPaymentData
     *
     * @throws ConfigException
     * @throws ResponseProcessingException
     * @throws Throwable
     * @throws HttpClientException
     * @throws NetworkException
     */
    public function __invoke(
        PaymentRequestData $paymentRequestData,
    ): AcquiringPaymentData {
        $detailPayment = $this->paymentService->createOrder($paymentRequestData);

        $response = $this->sberService->register($paymentRequestData);

        return $this->paymentService->updatePaymentDetails($detailPayment, $response);
    }
}
