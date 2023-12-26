<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Job;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JobControllerUpdateTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/vacancy/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $department = Department::factory()->create();

        /** @var Job $job */
        $job = Job::factory()->create();
        $job->department()->associate($department);
        $job->save();

        $title = fake()->title;

        $response = $this->put(
            self::METHOD . $job->getKey(),
            [
                'title' => $title,
                'salary' => (string)fake()->numberBetween(1000, 10000),
                'city' => fake()->name,
                'experience' => fake()->name,
                'description' => fake()->text,
                'slug' => fake()->slug,
                'department_id' => $department->getKey()
            ]
        );

        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) use ($job, $title) {
                    $json->hasAll([
                        'id',
                        'title',
                        'salary',
                        'city',
                        'experience',
                        'description',
                        'department',
                        'slug'
                    ])
                        ->where('id', $job->getKey())
                        ->where('title', $title);
                }
            );
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD . 100500);
        $response->assertServerError();
    }
}
