<?php

declare(strict_types=1);

namespace Modules\Payment\Repositories;

use App\Modules\Payment\Enums\PaymentStatusEnum;
use App\Modules\Payment\Models\PaymentStatus;
use App\Modules\Payment\Repositories\PaymentStatusRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\SberTestCase;

class AcquiringPaymentStatusRepositoryTest extends SberTestCase
{
    /**
     * @var PaymentStatusRepository
     */
    private PaymentStatusRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(PaymentStatusRepository::class);
    }

    /**
     * @test
     * Test that the find method of the repository returns a model.
     */
    public function testFindMethodReturnsModel(): void
    {
        $expectedColumns = ['id', 'full_name'];
        $expectedAttributesCount = count($expectedColumns);
        $model = $this->repository->find(PaymentStatusEnum::REGISTERED->value, $expectedColumns);
        $this->assertInstanceOf(PaymentStatus::class, $model);
        $this->assertEquals(PaymentStatusEnum::REGISTERED->value, $model->id);
        $this->assertCount($expectedAttributesCount, $model->getAttributes());
        $this->assertEquals($expectedColumns, array_keys($model->getAttributes()));
    }

    /**
     * Test if the findOrFail method returns the expected model.
     *
     * @test
     */
    public function testFindOrFailMethodReturnsModel()
    {
        $columns = ['id', 'name', 'created_at'];
        $expectedAttributesCount = 3;
        $model = $this->repository->find(PaymentStatusEnum::REVERSED->value, $columns);
        $this->assertInstanceOf(PaymentStatus::class, $model);
        $this->assertEquals(PaymentStatusEnum::REVERSED->value, $model->id);
        $this->assertCount($expectedAttributesCount, $model->getAttributes());
        $this->assertEquals($columns, array_keys($model->getAttributes()));
    }

    /**
     * @test
     */
    public function testFindOrFailMethodThrowsExceptionWhenCannotFindModel(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->find(9010, fail: true);
    }

    /**
     * @test
     */
    public function testFindByBankIdMethodReturnsModel(): void
    {
        $held = 1;
        $columns = ['id', 'name', 'full_name', 'created_at'];
        $model = $this->repository->findByBankId($held, $columns);
        $this->assertInstanceOf(PaymentStatus::class, $model);
        $this->assertSame(PaymentStatusEnum::HELD->value, $model->id);
        $this->assertCount(4, $model->getAttributes());
        $this->assertSame($columns, array_keys($model->getAttributes()));
    }
}
