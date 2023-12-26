<?php

declare(strict_types=1);

namespace App\Console\Commands\Payment;

use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentStatus;
use App\Modules\Payment\Repositories\PaymentRepository;
use App\Packages\Events\UpdateStatusCommandHasFailed;
use App\Packages\ModuleClients\ApiSberClientInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;
use Throwable;

class UpdateStatusCommand extends Command
{
    /**
     * Статусы по-умолчанию
     */
    public const STATUSES = [
        PaymentStatusEnum::NEW,
        PaymentStatusEnum::REGISTERED,
        PaymentStatusEnum::HELD,
        PaymentStatusEnum::ACS_AUTH,
    ];
    /**
     * @var string
     */
    protected $description = 'Update payments statuses.';
    /**
     * @var string
     */
    protected $signature =
        'sberbank-acquiring:update-statuses {--id=* : Only payments with specified status id will be updated}';

    /**
     * UpdateStatusCommand constructor.
     */
    public function __construct(
        private readonly ApiSberClientInterface $apiClient,
        private readonly PaymentRepository $paymentRepository
    ) {
        parent::__construct();
    }

    private function clearOutdatedpayments(): void
    {
        /**@var Builder $query*/
        $query = Payment::query()->whereNull('bank_order_id');
        $ids = $query->get()->pluck('id')->toArray();
        Payment::destroy($ids);
    }

    public function handle(): int
    {
        $this->clearOutdatedpayments();
        $this->comment('Start updating payments statuses...');
        $exceptions = [];
        $statuses = $this->getStatuses();
        $paymentStatuses = PaymentStatus::query()->whereNotIn('id', $statuses)->get();
        $payments = $this->paymentRepository->getByStatus($statuses);
        /** @var Payment $payment */
        foreach ($payments as $payment) {
            try {
                $orderId = $payment->bank_order_id;
                $sberbankResponse = $this->apiClient->getOrderStatusExtended($orderId);
                $response = $sberbankResponse->getData();
                if (! isset($response['orderStatus'])) {
                    throw new RuntimeException("Cannot find payment with orderId $orderId");
                }
                $statusId = $paymentStatuses->where('bank_id', $response['orderStatus'])->first()->id;
                $payment->update([
                    'status_id' => $statusId,
                ]);
            } catch (Throwable $e) {
                $this->error("Update payment with id $payment->id failed because: {$e->getMessage()}");
                $exceptions[] = $e->getMessage();
            }
        }
        if (! empty($exceptions)) {
            event(new UpdateStatusCommandHasFailed($exceptions));
            return 1;
        }
        return 0;
    }

    private function getStatuses(): array
    {
        $inputStatuses = $this->option('id');
        return empty($inputStatuses) ? self::STATUSES : $inputStatuses;
    }
}
