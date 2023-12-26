<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Department;

use App\Modules\Vacancies\Models\Department;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DepartmentControllerDeleteTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/department/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $department = Department::factory()->create();

        $response = $this->delete(self::METHOD . $department->getKey());
        $response->assertSuccessful();

        self::assertModelMissing($department);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
    }
}
