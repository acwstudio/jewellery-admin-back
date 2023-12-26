<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Payment\Models;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentOperation;
use App\Modules\Payment\Models\ApplePayPayment;
use App\Modules\Payment\Models\GooglePayPayment;
use App\Modules\Payment\Models\SamsungPayPayment;
use App\Modules\Payment\Models\SberbankPayment;

class PaymentsFactory
{
    /**
     * @return Payment
     */
    public function createAcquiringPayment(): Payment
    {
        return new Payment();
    }

    /**
     * @return SberbankPayment
     */
    public function createSberbankPayment(): SberbankPayment
    {
        return new SberbankPayment();
    }

    /**
     * @return ApplePayPayment
     */
    public function createApplePayPayment(): ApplePayPayment
    {
        return new ApplePayPayment();
    }

    /**
     * @return SamsungPayPayment
     */
    public function createSamsungPayPayment(): SamsungPayPayment
    {
        return new SamsungPayPayment();
    }

    /**
     * @return GooglePayPayment
     */
    public function createGooglePayPayment(): GooglePayPayment
    {
        return new GooglePayPayment();
    }

    /**
     * @return PaymentOperation
     */
    public function createPaymentOperation(): PaymentOperation
    {
        return new PaymentOperation();
    }
}
