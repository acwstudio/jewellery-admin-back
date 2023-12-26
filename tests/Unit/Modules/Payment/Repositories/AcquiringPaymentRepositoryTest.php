<?php

declare(strict_types=1);

namespace Modules\Payment\Repositories;

use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Repositories\PaymentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\SberTestCase;

class AcquiringPaymentRepositoryTest extends SberTestCase
{
    /**
     * @var PaymentRepository
     */
    private mixed $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(PaymentRepository::class);
    }

    /**
     * Test case for the find method in the repository.
     *
     * Verifies that the find method returns a model with the expected attributes.
     *
     * @test
     * @throws \Exception
     */
    public function findMethodReturnsModel(): void
    {
        // Create a mock acquiring payment with an empty string
        $acquiringPayment = $this->createAcquiringPayment();
        // Define the columns to be retrieved from the model
        $columns = ['id', 'bank_order_id'];
        // Call the find method on the repository
        $model = $this->repository->find($acquiringPayment->id, $columns);
        // Assert that the result is an instance of the Payment model
        $this->assertInstanceOf(Payment::class, $model);
        // Assert that the retrieved model has the expected ID
        $this->assertEquals($acquiringPayment->id, $model->id);
        // Assert that the retrieved model has the expected number of attributes
        $this->assertCount(2, $model->getAttributes());
        // Assert that the retrieved model has the expected attribute keys
        $this->assertEquals(['id', 'bank_order_id'], array_keys($model->getAttributes()));
    }

    /**
     * Test the findOrFail method of the repository.
     *
     * @throws \Exception
*/
    public function testFindOrFailMethodReturnsModel(): void
    {
        // Create a dummy acquiring payment
        $acquiringPayment = $this->createAcquiringPayment();
        // Specify the columns to retrieve
        $columns = ['id', 'status_id', 'system_id'];
        // Retrieve the model by ID with the specified columns
        $model = $this->repository->getById($acquiringPayment->id, $columns);
        // Assert that the returned model is an instance of Payment class
        $this->assertInstanceOf(Payment::class, $model);
        // Assert that the ID of the returned model matches the ID of the acquiring payment
        $this->assertEquals($acquiringPayment->id, $model->id);
        // Assert that the returned model has 3 attributes
        $this->assertCount(3, $model->getAttributes());
        // Assert that the keys of the returned model's attributes match the specified columns
        $this->assertEquals($columns, array_keys($model->getAttributes()));
    }

    /**
     * Test case for the find_or_fail_method_throws_exception_when_cannot_find_model function.
     *
     * @test
     */
    public function findOrFailMethodThrowsExceptionWhenCannotFindModel(): void
    {
        // Expect an exception of type ModelNotFoundException to be thrown
        $this->expectException(ModelNotFoundException::class);
        // Call the getById method of the repository with an invalid ID
        $model = $this->repository->getById(1001231, fail: true);
    }

    /**
     * @test
     * Refactored test case for the get_by_status_method.
     * Ensures that the getByStatus method of the repository returns the correct payments collection based on the
     *     status.
     * @throws \Exception
     */
    public function testGetByStatusMethodReturnsPaymentsCollection(): void
    {
        // Create test payments with different status
        $newPayment = $this->createAcquiringPayment(attributes:['status_id' => PaymentStatusEnum::NEW]);
        $registeredPayment = $this->createAcquiringPayment(attributes:['status_id' => PaymentStatusEnum::REGISTERED]);
        $registeredPayment2 = $this->createAcquiringPayment(attributes:['status_id' => PaymentStatusEnum::REGISTERED]);
        $errorPayment = $this->createAcquiringPayment(attributes:['status_id' => PaymentStatusEnum::ERROR]);
        // Get payments by status
        $newPayments = $this->repository->getByStatus([PaymentStatusEnum::NEW]);
        $registeredPayments = $this->repository->getByStatus([PaymentStatusEnum::REGISTERED]);
        $errorPayments = $this->repository->getByStatus([PaymentStatusEnum::ERROR]);
        $acsAuthPayments = $this->repository->getByStatus([PaymentStatusEnum::ACS_AUTH]);
        // Assertions for the payments collection
        $this->assertCount(1, $newPayments);
        $this->assertTrue($newPayments->contains($newPayment));
        $this->assertCount(2, $registeredPayments);
        $this->assertTrue($registeredPayments->contains($registeredPayment));
        $this->assertTrue($registeredPayments->contains($registeredPayment2));
        $this->assertCount(1, $errorPayments);
        $this->assertTrue($errorPayments->contains($errorPayment));
        $this->assertCount(0, $acsAuthPayments);
    }

    /**
     * Test the get_by_status_method to ensure it returns a payments collection with specified columns.
     *
     * @test
     */
    public function testGetByStatusMethodReturnsPaymentsCollectionWithSpecifiedColumns(): void
    {
        // Create a new acquiring payment with a specified status
        $payment = $this->createAcquiringPayment(attributes:['status_id' => PaymentStatusEnum::NEW]);
        // Retrieve payments with the specified status and columns
        $payments = $this->repository->getByStatus([PaymentStatusEnum::NEW], ['id', 'bank_order_id']);
        // Assert that the payments collection contains the created payment
        $this->assertTrue($payments->contains($payment));
        // Get the attributes of the first payment in the collection
        $attributes = array_keys($payments->first()->getAttributes());
        // Assert that the attributes match the specified columns
        $this->assertEquals(['id', 'bank_order_id'], $attributes);
    }
}
