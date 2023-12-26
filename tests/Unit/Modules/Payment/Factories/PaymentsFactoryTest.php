<?php

declare(strict_types=1);

namespace Modules\Payment\Factories;

use App\Modules\Payment\Models\PaymentOperation;
use Database\Factories\Modules\Payment\Models\PaymentsFactory;
use Tests\SberTestCase;

class PaymentsFactoryTest extends SberTestCase
{
    /**
     * @var PaymentsFactory
     */
    private PaymentsFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new PaymentsFactory();
    }

    /**
     * @test
     */
    public function testItCreatesNewAcquiringPaymentModel(): void
    {
        $acquiringPayment = $this->factory->createAcquiringPayment();
        $this->assertFalse($acquiringPayment->exists);
    }

    /**
     * @test
     */
    public function testItCreatesNewSberbankPaymentModel(): void
    {
        $this->assertFalse($this->factory->createSberbankPayment()->exists);
    }

    /**
     * @test
     */
    public function testItCreatesNewApplePayPaymentModel(): void
    {
        $this->assertFalse($this->factory->createApplePayPayment()->exists);
    }

    /**
     * @test
     */
    public function testItCreatesNewSamsungPayPaymentModel(): void
    {
        $this->assertFalse($this->factory->createSamsungPayPayment()->exists);
    }

    /**
     * @test
     */
    public function testItCreatesNewGooglePayPaymentModel(): void
    {
        $googlePayPayment = $this->factory->createGooglePayPayment();
        $this->assertFalse($googlePayPayment->exists);
    }

    /**
     * @test
     */
    public function testCreateAcquiringPaymentOperationModel(): void
    {
        $operation = $this->createPaymentOperation();
        $this->assertFalse($operation->exists);
    }

    private function createPaymentOperation(): PaymentOperation
    {
        return $this->factory->createPaymentOperation();
    }
}
