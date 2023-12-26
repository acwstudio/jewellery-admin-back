<?php

declare(strict_types=1);

namespace App\Modules\Payment\Repositories;

use App\Modules\Payment\Models\PaymentOperation;
use App\Modules\Users\UsersModuleClient;
use Database\Factories\Modules\Payment\Models\PaymentsFactory;
use Throwable;

class PaymentOperationRepository
{
    /**
     * @param  \Database\Factories\Modules\Payment\Models\PaymentsFactory  $paymentsFactory
     * @param  \App\Modules\Users\UsersModuleClient  $client
     */
    public function __construct(
        private readonly PaymentsFactory $paymentsFactory,
        private readonly UsersModuleClient $client
    ) {
    }

    /**
     * @throws Throwable
     */
    public function create(
        $operationId,
        $requestData,
        $acquiringPayment
    ): PaymentOperation {
        $operation = $this->paymentsFactory->createPaymentOperation();
        $operation->fill([
            'user_id'      => $this->client->getUser()->user_id,
            'type_id'      => $operationId,
            'request_json' => $requestData,
        ]);
        $operation->payment()->associate($acquiringPayment);
        $operation->saveOrFail();

        return $operation;
    }
}
