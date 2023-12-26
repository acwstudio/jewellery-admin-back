<?php

declare(strict_types=1);

namespace Http\Controllers\Questionnaire\Survey;

use App\Modules\Questionnaire\Enums\SurveyTypeEnum;
use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Models\Survey;
use Tests\TestCase;

class SurveyControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/questionnaire/survey/';

    public function testSuccessful()
    {
        $survey = Survey::factory()->create([
            'type' => SurveyTypeEnum::EXTERNAL->value
        ]);

        Question::factory(3)->create([
            'survey_uuid' => $survey->getKey(),
            'options' => [
                ['name' => 'Плохо', 'value' => '0'],
                ['name' => 'Хорошо', 'value' => '5']
            ]
        ]);

        $response = $this->get(self::METHOD . $survey->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('uuid', $content);
        self::assertArrayHasKey('title', $content);
        self::assertArrayHasKey('questions', $content);
        self::assertIsArray($content['questions']);
        foreach ($content['questions'] as $question) {
            self::assertArrayHasKey('uuid', $question);
            self::assertArrayHasKey('value', $question);
            self::assertArrayHasKey('options', $question);
            self::assertArrayHasKey('code', $question);
            self::assertIsArray($question['options']);

            foreach ($question['options'] as $option) {
                self::assertArrayHasKey('name', $option);
                self::assertArrayHasKey('value', $option);
            }
        }
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . fake()->uuid);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
