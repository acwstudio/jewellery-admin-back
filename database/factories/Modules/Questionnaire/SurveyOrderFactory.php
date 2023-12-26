<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Questionnaire;

use App\Modules\Orders\Models\Order;
use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Models\CompletedSurvey;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyOrderFactory extends Factory
{
    protected $model = CompletedSurvey::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'survey_uuid' => Survey::factory(),
            'order_id' => Order::factory(),
        ];
    }
}
