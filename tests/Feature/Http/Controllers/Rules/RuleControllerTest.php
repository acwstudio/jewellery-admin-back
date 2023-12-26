<?php

declare(strict_types=1);

namespace Http\Controllers\Rules;

use App\Modules\Rules\Models\Rule;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RuleControllerTest extends TestCase
{
    private $admin;
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
    }

    public function testGetRule()
    {
        $rule = Rule::factory()->create();

        /**
         * @phpstan-ignore-next-line
         */
        $response = $this->get(route('api.v1.rules.rule.show', $rule->id));

        $response
            ->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) use ($rule) {
                    $json
                        ->hasAll(
                            [
                                'id',
                                'title',
                                'description',
                                'country',
                                'date_start',
                                'date_finish',
                                'slug'
                            ]
                        )
                    /**
                * @phpstan-ignore-next-line
                */
                        ->where('id', $rule->id);
                }
            );
    }

    public function testDeleteRule()
    {
        $rule = Rule::factory()->create();

        /**
         * @phpstan-ignore-next-line
         */
        $response = $this->actingAs($this->admin)->delete(route('api.v1.rules.rule.destroy', $rule->id));

        $response->assertStatus(200);
        $this->assertNull(
            /**
             * @phpstan-ignore-next-line
             */
            Rule::find($rule->id)
        );
    }

    public function testGetAllRule()
    {
        $rules = Rule::factory()->count(3)->create();

        $ids = $rules->pluck('id');

        $response = $this->get(route('api.v1.rules.rule.index'));
        $response
            ->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) use ($ids) {
                    $json
                    ->has(count($ids));
                }
            );
    }

    public function testCreateRule()
    {
        $title = fake()->title;

        $response = $this->actingAs($this->admin)->post(
            route('api.v1.rules.rule.store'),
            [
                'title' => $title,
                'description' => fake()->title,
                'country' => fake()->country,
                'date_start' => fake()->date,
                'date_finish' => fake()->date,
                'slug' => fake()->slug
            ]
        );

        $response->assertStatus(201)
            ->assertJson(
                function (AssertableJson $json) use ($title) {
                    $json->hasAll(
                        [
                            'id',
                            'title',
                            'description',
                            'country',
                            'date_start',
                            'date_finish',
                            'slug'
                        ]
                    )
                        ->where('title', $title);
                }
            );
    }

    public function testUpdateRule()
    {
        $rule = Rule::factory()->create();

        $title = fake()->title;


        $response = $this->actingAs($this->admin)->put(
            /**
             * @phpstan-ignore-next-line
             */
            route('api.v1.rules.rule.update', $rule->id),
            [
                'title' => $title,
                'description' => fake()->title,
                'country' => fake()->country,
                'date_start' => fake()->date,
                'date_finish' => fake()->date,
                'slug' => fake()->slug
            ]
        );
        $response->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) use ($rule, $title) {
                    $json->hasAll(
                        [
                            'id',
                            'title',
                            'description',
                            'country',
                            'date_start',
                            'date_finish',
                            'slug'
                        ]
                    )
                    /**
                * @phpstan-ignore-next-line
                */
                        ->where('id', $rule->id)
                        ->where('title', $title);
                }
            );
    }

    public function testGetRuleBySlug()
    {
        $rule = Rule::factory()->create();

        /**
         * @phpstan-ignore-next-line
         */
        $response = $this->get(route('api.v1.rules.rule.slug', $rule->slug));

        $response
            ->assertStatus(200)
            ->assertJson(
                function (AssertableJson $json) use ($rule) {
                    $json
                        ->hasAll(
                            [
                                'id',
                                'title',
                                'description',
                                'country',
                                'date_start',
                                'date_finish',
                                'slug'
                            ]
                        )
                        /**
                         * @phpstan-ignore-next-line
                         */
                        ->where('slug', $rule->slug);
                }
            );
    }
}
