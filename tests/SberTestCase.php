<?php

declare(strict_types=1);

namespace Tests;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Models\ApplePayPayment;
use App\Modules\Payment\Models\GooglePayPayment;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentOperation;
use App\Modules\Payment\Models\SamsungPayPayment;
use App\Modules\Payment\Models\SberbankPayment;
use Database\Factories\Modules\Payment\PaymentFactory;
use Mockery\MockInterface;
use ReflectionException;
use ReflectionProperty;

class SberTestCase extends TestCase
{
    /**
     * @throws \Exception
     */
    protected function createAcquiringPayment(?PaymentTypeEnum $paymentType = null, ?array $attributes = [])
    {
        $payment  = $this->createPayment();
        if ($paymentType === null) {
            return $payment->create($attributes);
        }
        return $payment->state($payment->getCallable($paymentType))
            ->create($attributes);
    }


    protected function createPayment(): PaymentFactory
    {
        return PaymentFactory::new();
    }

    protected function createSberbankPayment(array $attributes = [])
    {
        return SberbankPayment::factory()->create($attributes);
    }

    protected function createApplePayPayment(array $attributes = [])
    {
        return ApplePayPayment::factory()->create($attributes);
    }

    protected function createSamsungPayPayment(array $attributes = [])
    {
        return SamsungPayPayment::factory()->create($attributes);
    }

    protected function createGooglePayPayment(array $attributes = [])
    {
        return GooglePayPayment::factory()->create($attributes);
    }

    protected function createAcquiringPaymentOperation(array $attributes = [])
    {
        return PaymentOperation::factory()->create($attributes);
    }

    protected function mockAcquiringPayment(string $method, $returnValue): MockInterface
    {
        return $this->mock(Payment::class, function (MockInterface $mock) use ($method, $returnValue) {
            $mock->allows($method)->andReturn($returnValue);
        });
    }

    protected function mockSberbankPayment(string $method, $returnValue): MockInterface
    {
        return $this->mock(SberbankPayment::class, function (MockInterface $mock) use ($method, $returnValue) {
            $mock->allows($method)->andReturn($returnValue);
        });
    }

    public function mockAcquiringPaymentOperation(string $method, $returnValue): MockInterface
    {
        return $this->partialMock(
            PaymentOperation::class,
            function (MockInterface $mock) use ($method, $returnValue) {
                $mock->expects($method)
                    ->andReturn($returnValue);
            }
        );
    }

    /**
     * Sets the value of a protected property of an object
     *
     * @param  object  $object
     * @param  string  $property
     * @param  mixed  $value
     *
     * @throws ReflectionException
     */
    protected function setProtectedProperty(object $object, string $property, mixed $value): void
    {
        (new ReflectionProperty($object, $property))->setValue($object, $value);
    }
}
