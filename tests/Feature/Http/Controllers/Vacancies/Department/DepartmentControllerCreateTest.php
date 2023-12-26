<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Department;

use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DepartmentControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/department';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $title = fake()->title;

        $response = $this->post(self::METHOD, ['title' => $title]);

        $response
            ->assertSuccessful()
            ->assertJson(
                function (AssertableJson $json) use ($title) {
                    $json->hasAll(['id', 'title'])
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
