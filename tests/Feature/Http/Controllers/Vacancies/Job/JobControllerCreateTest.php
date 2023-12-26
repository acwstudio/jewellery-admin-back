<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Job;

use App\Modules\Vacancies\Models\Department;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JobControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/vacancy';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $department = Department::factory()->create();

        $title = fake()->title;
        $response = $this->post(
            self::METHOD,
            [
                'title' => $title,
                'salary' => $title,
                'city' => $title,
                'experience' => $title,
                'description' => $title,
                'slug' => fake()->slug,
                'department_id' => $department->getKey()
            ]
        );

        $response
            ->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($title) {
                    $json->hasAll([
                        'id',
                        'title',
                        'salary',
                        'city',
                        'experience',
                        'description',
                        'slug',
                        'department'
                    ])
                        ->where('title', $title);
                }
            );
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
    }
}
