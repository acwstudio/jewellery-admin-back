<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentOperationType;
use Tests\SberTestCase;

/**
 *
 */
class PaymentOperationTest extends SberTestCase
{
    /**
     * @test
     */
    public function testItHasUserRelation(): void
    {
        $user = $this->getUser();
        $acquiringPaymentOperation = $this->createAcquiringPaymentOperation(['user_id' => $user->getKey()]);
        $this->assertInstanceOf(
            config('sberbank-acquiring.user.model'),
            $acquiringPaymentOperation->user
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itHasPaymentRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment();
        $acquiringPaymentOperation = $this->createAcquiringPaymentOperation(['payment_id' => $acquiringPayment->id]);
        $payment = $acquiringPaymentOperation->payment;
        $this->assertInstanceOf(Payment::class, $payment);
    }

    /**
     * @test
     */
    public function itHasTypeRelation(): void
    {
        $acquiringPaymentOperation = $this->createAcquiringPaymentOperation();
        $this->assertIsObject($acquiringPaymentOperation->type);
        $this->assertInstanceOf(PaymentOperationType::class, $acquiringPaymentOperation->type);
    }
}
