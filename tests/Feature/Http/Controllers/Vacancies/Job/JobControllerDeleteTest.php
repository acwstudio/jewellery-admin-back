<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Job;

use App\Modules\Vacancies\Models\Job;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JobControllerDeleteTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/vacancy/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $job = Job::factory()->create();

        $response = $this->delete(self::METHOD . $job->getKey());
        $response->assertSuccessful();

        self::assertModelMissing($job);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
    }
}
