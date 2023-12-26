<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Job;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class JobControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/vacancy';

    public function testSuccessful()
    {
        $department = Department::factory()->create();
        $jobs = Job::factory(3)->create();

        /** @var Job $job */
        foreach ($jobs as $job) {
            $job->department()->associate($department);
            $job->save();
        }

        $ids = $jobs->pluck('id');

        $response = $this->get(self::METHOD);
        $response
            ->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($ids) {
                    $json
                        ->has(count($ids));
                }
            );
    }
}
