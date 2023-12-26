<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Department;

use App\Modules\Vacancies\Models\Department;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DepartmentControllerUpdateTest extends TestCase
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

        $title = fake()->title;

        $response = $this->put(self::METHOD . $department->getKey(), ['title' => $title]);
        $response
            ->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($department, $title) {
                    $json->hasAll(['id', 'title'])
                        /**
                         * @phpstan-ignore-next-line
                         */
                        ->where('id', $department->id)
                        ->where('title', $title);
                }
            );
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD);
        $response->assertServerError();
    }
}
