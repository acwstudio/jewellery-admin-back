<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Models\ApplePayPayment;
use App\Modules\Payment\Models\GooglePayPayment;
use App\Modules\Payment\Models\PaymentStatus;
use App\Modules\Payment\Models\PaymentSystem;
use App\Modules\Payment\Models\SamsungPayPayment;
use App\Modules\Payment\Models\SberbankPayment;
use Tests\SberTestCase;

class AcquiringPaymentTest extends SberTestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function testItHasOperationsRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment();
        $acquiringPaymentOperation = $this->createAcquiringPaymentOperation(['payment_id' => $acquiringPayment->id]);
        $operations = $acquiringPayment->operations;
        $this->assertTrue($operations->contains($acquiringPaymentOperation));
        $this->assertCount(1, $operations);
    }

    /**
     * @test
     */
    public function itHasSystemRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment();
        $this->assertInstanceOf(
            PaymentSystem::class,
            $acquiringPayment->system
        );
    }

    /**
     * @test
     */
    public function itHasStatusRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment();
        $this->assertInstanceOf(PaymentStatus::class, $acquiringPayment->status);
    }

    /**
     * @test
     */
    public function itHasSberbankPaymentMorphRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment();
        $this->assertInstanceOf(SberbankPayment::class, $acquiringPayment->payment);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itHasApplePayPaymentMorphRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment(PaymentTypeEnum::APPLE_PAY);
        $this->assertInstanceOf(ApplePayPayment::class, $acquiringPayment->payment);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itHasSamsungPayPaymentMorphRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment(PaymentTypeEnum::SAMSUNG_PAY);
        $this->assertInstanceOf(SamsungPayPayment::class, $acquiringPayment->payment);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itHasGooglePayPaymentMorphRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment(PaymentTypeEnum::GOOGLE_PAY);
        $this->assertInstanceOf(GooglePayPayment::class, $acquiringPayment->payment);
    }
}
