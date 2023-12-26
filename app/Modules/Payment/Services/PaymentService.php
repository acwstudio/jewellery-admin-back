<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Enums\PaymentOperationTypeEnum;
use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Enums\PaymentSystemTypeEnum;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentOperation;
use App\Modules\Payment\Models\SberbankPayment;
use App\Modules\Payment\Repositories\PaymentRepository;
use App\Modules\Payment\Repositories\PaymentStatusRepository;
use App\Packages\DataObjects\Payment\AcquiringPaymentData;
use App\Packages\DataObjects\Payment\PaymentDetailData;
use App\Packages\DataObjects\Payment\PaymentRequestData;
use App\Packages\Events\PaymentStatusChanged;
use Database\Factories\Modules\Payment\Models\PaymentsFactory;
use Illuminate\Support\Facades\Auth;
use JsonException;
use Throwable;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentStatusRepository $paymentStatusRepository,
        private readonly PaymentsFactory $paymentsFactory
    ) {
    }

    public function getPaymentById(int $id): Payment
    {
        return $this->paymentRepository->getById(id: $id, fail: true);
    }

    public function getPaymentStatusByBankId(int $id)
    {
        return $this->paymentStatusRepository->findByBankId($id);
    }

    public function getPaymentByBankOrderId(string $bankOrderId): ?Payment
    {
        return $this->paymentRepository->getByBankOrderId($bankOrderId, true);
    }

    /**
     * @throws Throwable
     */
    public function createOrder($requestData): PaymentDetailData
    {
        $sberbankPayment = $this->createSberbankPayment($requestData);
        $acquiringPayment = $this->createAcquiringPayment($sberbankPayment);
        $operation = $this->createPaymentOperation(
            $requestData,
            $acquiringPayment
        );
        return new PaymentDetailData(
            $sberbankPayment,
            $acquiringPayment,
            $operation
        );
    }

    /**
     * @throws Throwable
     */
    public function createSberbankPayment(PaymentRequestData $requestData): SberbankPayment
    {
        $payment = $this->paymentsFactory->createSberbankPayment();
        $payment->fillWithSberbankParams($requestData->params->toArray());
        $payment->saveOrFail();
        return $payment;
    }

    /**
     * @throws Throwable
     */
    private function createAcquiringPayment($payment): Payment
    {
        $acquiringPayment = $this->paymentsFactory->createAcquiringPayment();
        $acquiringPayment->fill([
            'system_id' => PaymentSystemTypeEnum::SBERBANK->value,
            'status_id' => PaymentStatusEnum::NEW->value,
        ]);
        $acquiringPayment->payment()->associate($payment);
        $acquiringPayment->saveOrFail();
        return $acquiringPayment;
    }

    /**
     * @throws Throwable
     */
    private function createPaymentOperation(
        $requestData,
        $acquiringPayment
    ): PaymentOperation {
        $operation = $this->paymentsFactory->createPaymentOperation();
        $operation->fill([
            'user_id'      => Auth::id(),
            'type_id'      => PaymentOperationTypeEnum::REGISTER,
            'request_json' => $requestData,
        ]);
        $operation->payment()->associate($acquiringPayment);
        $operation->saveOrFail();
        return $operation;
    }

    /**
     * @throws JsonException
     */
    public function updatePaymentDetails(
        PaymentDetailData $detailPayment,
        array $response
    ): AcquiringPaymentData {
        $orderId = $response['orderId'];
        $formUrl = $response['formUrl'];
        $payment = $detailPayment->acquiringPayment;
        $paymentSberbank = $detailPayment->sberbankPayment;
        $paymentOperation = $detailPayment->paymentOperation;
        if ($this->isOk($response)) {
            $acquiringPaymentSaved = $payment
                ->update([
                    'bank_order_id' => $orderId,
                    'status_id'     => PaymentStatusEnum::REGISTERED->value,
                ]);
            $paymentSberbank->update(['bank_form_url' => $formUrl]);
        } else {
            $payment->update([
                'status_id' => PaymentStatusEnum::ERROR->value,
            ]);
        }
        $paymentOperation->update([
            'response_json' => json_encode(
                $response,
                JSON_THROW_ON_ERROR
            ),
        ]);
        return new AcquiringPaymentData(
            url: $formUrl,
            payment_id: $detailPayment->acquiringPayment->id
        );
    }

    public function updatePaymentStatus(Payment $payment, PaymentStatusEnum $status): void
    {
        $this->paymentRepository->updateStatus($payment, $status);
        PaymentStatusChanged::dispatch($payment->id);
    }

    private function isOk($response): bool
    {
        return $this->getErrorCode($response) === 0;
    }

    private function getErrorCode($response): int
    {
        $responseData = $response;
        if (isset($responseData['errorCode'])) {
            return (int)$responseData['errorCode'];
        }
        if (isset($responseData['error']['code'])) {
            return (int)$responseData['error']['code'];
        }
        return 0;
    }
}
