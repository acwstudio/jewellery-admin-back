<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Questionnaire;

use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'survey_uuid' => Survey::factory(),
            'value' => $this->faker->sentence(),
            'options' => [],
            'code' => $this->faker->slug(1)
        ];
    }
}
