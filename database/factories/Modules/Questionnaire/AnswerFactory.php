<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Questionnaire;

use App\Modules\Questionnaire\Models\Answer;
use App\Modules\Questionnaire\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'question_uuid' => Question::factory(),
            'identifier' => $this->faker->uuid(),
            'value' => $this->faker->text,
        ];
    }
}
