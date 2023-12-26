<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Department;

use App\Modules\Vacancies\Models\Department;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DepartmentControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/department';

    public function testSuccessful()
    {
        $departments = Department::factory()->count(3)->create();
        $ids = $departments->pluck('id');

        $response = $this->get(self::METHOD);
        $response->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($ids) {
                    $json
                        ->has(count($ids));
                }
            );
    }
}
