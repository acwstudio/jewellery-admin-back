<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Job;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class JobControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/vacancy/';

    public function testSuccessful()
    {
        $department = Department::factory()->create();

        /** @var Job $job */
        $job = Job::factory()->create();
        $job->department()->associate($department);
        $job->save();

        $response = $this->get(self::METHOD . $job->getKey());
        $response
            ->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($job) {
                    $json
                        ->hasAll(['id', 'title', 'salary', 'city', 'experience', 'description', 'department', 'slug'])
                        ->where('id', $job->getKey());
                }
            );
    }
}
