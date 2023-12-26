<?php

declare(strict_types=1);

namespace App\Modules\Payment;

use App\Modules\Payment\Services\PaymentService;
use App\Modules\Payment\UseCases\GetOrderStatusExtended;
use App\Modules\Payment\UseCases\PaymentUpdateStatus;
use App\Modules\Payment\UseCases\PublishPaymentStatus;
use App\Modules\Payment\UseCases\RegisterOrder;
use App\Packages\ApiClients\Payment\Responses\Callbacks\SberbankCallbackStatusData;
use App\Packages\DataObjects\Payment\AcquiringPaymentData;
use App\Packages\DataObjects\Payment\PaymentData;
use App\Packages\DataObjects\Payment\PaymentRequestData;
use App\Packages\ModuleClients\PaymentModuleClientInterface;
use Illuminate\Support\Facades\App;

class PaymentModuleClient implements PaymentModuleClientInterface
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {
    }

    /**
     * Регистрация заказа
     */
    public function register(PaymentRequestData $paymentRequestData): AcquiringPaymentData
    {
        return App::call(RegisterOrder::class, ['paymentRequestData' => $paymentRequestData]);
    }

    public function getOrderStatusExtended($id)
    {
        return App::call(GetOrderStatusExtended::class, ['id' => $id]);
    }

    public function webhookStatus(SberbankCallbackStatusData $data): void
    {
        App::call(PaymentUpdateStatus::class, ['data' => $data]);
    }

    public function publishPaymentStatus(int $id): void
    {
        App::call(PublishPaymentStatus::class, ['id' => $id]);
    }

    public function getPayment(int $id): PaymentData
    {
        $payment = $this->paymentService->getPaymentById($id);

        return PaymentData::fromModel($payment);
    }
}
