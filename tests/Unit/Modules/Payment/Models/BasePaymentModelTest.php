<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use App\Modules\Payment\Models\BasePaymentModel;
use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;
use Tests\SberTestCase;

class BasePaymentModelTest extends SberTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testFillWithSberbankParamsThrowsExceptionWhenGetsUnknownParam(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $model = $this
            ->getMockBuilder(BasePaymentModel::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $reflectionProperty = new ReflectionProperty($model, 'acquiringParamsMap');
        $reflectionProperty->setValue($model, ['sberParam' => 'sber_param']);
        $model->fillWithSberbankParams(['unknownParam' => 'value']);
    }

    /**
     * @test
     * @throws ReflectionException
     */
    public function fillWithSberbankParamsFillsFillableAttributes(): void
    {
        $model = $this->getMockForAbstractClass(BasePaymentModel::class, [], '', false);
        $model->fillable(['sber_param_2']);
        $this->setProtectedProperty(
            $model,
            'acquiringParamsMap',
            ['sberParam' => 'sber_param', 'sberParam2' => 'sber_param_2']
        );
        $model->fillWithSberbankParams(['sberParam' => 'param', 'sberParam2' => 'param2']);
        $this->assertEquals(['sber_param_2' => 'param2'], $model->getAttributes());
    }
}
