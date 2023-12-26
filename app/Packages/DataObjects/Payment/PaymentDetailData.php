<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Payment;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentOperation;
use App\Modules\Payment\Models\SberbankPayment;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'payment_detail_data', type: 'object')]
class PaymentDetailData extends Data
{
    public function __construct(
        #[Property('sberPayment', type: 'object')]
        public readonly SberbankPayment $sberbankPayment,
        #[Property('acquiringPayment', type: 'object')]
        public readonly Payment $acquiringPayment,
        #[Property('paymentOperation', type: 'object')]
        public readonly PaymentOperation $paymentOperation,
    ) {
    }
}
