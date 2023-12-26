<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Questionnaire;

use App\Modules\Questionnaire\Enums\SurveyTypeEnum;
use App\Modules\Questionnaire\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyFactory extends Factory
{
    protected $model = Survey::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'type' => $this->faker->randomElement(SurveyTypeEnum::cases())->value,
        ];
    }
}
