<?php

declare(strict_types=1);

namespace App\Modules\Payment\UseCases;

use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Services\PaymentService;
use App\Packages\DataObjects\Payment\Messages\PaymentStatusMessageData;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use Money\Money;
use Psr\Log\LoggerInterface;

class PublishPaymentStatus
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(int $id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            $this->preCheck($payment);
            $this->send($payment);
        } catch (\Throwable $e) {
            $this->logger->alert(
                "[x] Failed publish Payment Status #$id",
                ['exception' => $e]
            );
        }

        $this->logger->info("[+] Published Payment Status #$id");
    }

    private function preCheck(Payment $payment): void
    {
        if (PaymentStatusEnum::CONFIRMED !== $payment->status_id) {
            throw new \Exception('Payment Status not Confirmed #status_id: ' . $payment->status_id);
        }
    }

    private function send(Payment $payment): void
    {
        $message = $this->prepareBody($payment);
        $queue = config('export.queues.payment_statuses');
        $this->AMQPModuleClient->publish($queue, $message);
    }

    private function prepareBody(Payment $payment): string
    {
        $data = new PaymentStatusMessageData(
            order_number: $payment->payment->order_number,
            payment_date: $payment->updated_at->toRfc3339String(),
            transaction: $payment->bank_order_id,
            cashier_name: $payment->system?->name ?? 'Неизвестно',
            payment_amount: Money::RUB($payment->payment->amount),
            payment_type: PaymentTypeEnum::SBER_PAY->value
        );

        return $data->toJson();
    }
}
