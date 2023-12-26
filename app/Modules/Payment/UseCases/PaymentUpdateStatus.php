<?php

declare(strict_types=1);

namespace App\Modules\Payment\UseCases;

use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Services\PaymentService;
use App\Packages\ApiClients\Payment\Enums\OperationEnum;
use App\Packages\ApiClients\Payment\Responses\Callbacks\SberbankCallbackStatusData;
use App\Packages\ModuleClients\ApiSberClientInterface;
use Psr\Log\LoggerInterface;

class PaymentUpdateStatus
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly ApiSberClientInterface $apiSberClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(SberbankCallbackStatusData $data): void
    {
        $this->logger->info('[PaymentUpdateStatus] REQUEST', ['all' => request()->all()]);
        try {
            if (!$this->apiSberClient->isCorrectCheckSumCallback($data)) {
                throw new \Exception('CheckSum not corrected.');
            }

            $this->logger->info('PaymentUpdateStatus', ['data' => $data->toArray()]);
            $payment = $this->paymentService->getPaymentByBankOrderId($data->mdOrder);
            $status = $this->getPaymentStatus($data);

            if (null === $status) {
                throw new \Exception('Status not supported.');
            }

            $this->paymentService->updatePaymentStatus($payment, $status);
        } catch (\Throwable $e) {
            $this->logger->error('PaymentUpdateStatus. ERROR', [
                'message' => $e->getMessage(),
                'data' => $data->toArray()
            ]);
        }
    }

    private function getPaymentStatus(SberbankCallbackStatusData $data): ?PaymentStatusEnum
    {
        if (!$data->status) {
            return PaymentStatusEnum::ERROR;
        }

        return match ($data->operation) {
            OperationEnum::CREATED => PaymentStatusEnum::REGISTERED,
            OperationEnum::APPROVED => PaymentStatusEnum::HELD,
            OperationEnum::DEPOSITED => PaymentStatusEnum::CONFIRMED,
            OperationEnum::REVERSED => PaymentStatusEnum::REVERSED,
            OperationEnum::REFUNDED => PaymentStatusEnum::REFUNDED,
            OperationEnum::DECLINED_BY_TIMEOUT => PaymentStatusEnum::DECLINED,
            default => null
        };
    }
}
