<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Department;

use App\Modules\Vacancies\Models\Department;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DepartmentControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/department/';

    public function testSuccessful()
    {
        /** @var Department $department */
        $department = Department::factory()->create();

        $response = $this->get(self::METHOD . $department->getKey());
        $response
            ->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($department) {
                    $json
                        ->hasAll(['id', 'title'])
                        ->where('id', $department->getKey());
                }
            );
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . 100500);
        $response->assertServerError();
    }
}
