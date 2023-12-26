<?php

declare(strict_types=1);

namespace Http\Controllers\Questionnaire\Answer;

use App\Modules\Questionnaire\Enums\SurveyTypeEnum;
use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Models\Survey;
use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use Mockery\MockInterface;
use Tests\TestCase;

class AnswerControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/questionnaire/answer';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockMindboxApiClient();
    }

    public function testSuccessful()
    {
        $survey = $this->getSurvey();
        $answers = $this->createAnswers($survey);

        $response = $this->post(
            self::METHOD,
            [
                'survey_uuid' => $survey->getKey(),
                'identifier' => '79278899489',
                'answers' => $answers
            ],
            [
                'User-Agent' => 'Tests-Feature-Http',
                'x-device-uuid' => fake()->uuid()
            ]
        );
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD, [
            'survey_uuid' => '',
            'identifier' => '+79278899489',
            'answers' => []
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNotFoundSurvey()
    {
        $answers = [
            [
                'question_uuid' => fake()->uuid,
                'value' => 'test',
                'comment' => fake()->text(10)
            ]
        ];

        $response = $this->post(self::METHOD, [
            'survey_uuid' => fake()->uuid,
            'identifier' => '+79278899489',
            'answers' => $answers
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyAnswers()
    {
        $survey = $this->getSurvey();

        $response = $this->post(self::METHOD, [
            'survey_uuid' => $survey->getKey(),
            'identifier' => '+79278899489',
            'answers' => []
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureIdentifierRequired()
    {
        $survey = $this->getSurvey();
        $answers = $this->createAnswers($survey);

        $response = $this->post(self::METHOD, [
            'survey_uuid' => $survey->getKey(),
            'identifier' => null,
            'answers' => $answers
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertEquals('questionnaire_module_identifier_required_exception', $content['error']['code']);
    }

    private function getSurvey(): Survey
    {
        /** @var Survey $survey */
        $survey = Survey::factory()->create([
            'type' => SurveyTypeEnum::EXTERNAL->value
        ]);

        $codes = [
            'impressionsOfShopping' => true,
            'assortment' => true,
            'experts' => true,
            'atmosphere' => true,
            'comfortableChoosing' => true,
            'makingAPurchase' => true,
            'communicating' => true,
            'shop' => false,
        ];

        foreach ($codes as $code => $setOptions) {
            $this->createQuestion($survey, $setOptions, $code);
        }

        return $survey->refresh();
    }

    private function createQuestion(Survey $survey, bool $setOptions = true, ?string $code = null): void
    {
        $options = [];
        if ($setOptions) {
            $options = [
                ['name' => 'Option 1', 'value' => '1'],
                ['name' => 'Option 2', 'value' => '2'],
                ['name' => 'Option 3', 'value' => '3'],
            ];
        }

        Question::factory()->create([
            'survey_uuid' => $survey->getKey(),
            'code' => $code,
            'options' => $options
        ]);
    }

    private function createAnswers(Survey $survey): array
    {
        $answers = [];
        /** @var \App\Modules\Questionnaire\Models\Question $question */
        foreach ($survey->questions as $question) {
            $value = '';
            if (!empty($question->options)) {
                $value = $question->options[array_rand($question->options)]['value'] ?? '';
            }
            $answers[] = [
                'question_uuid' => $question->uuid,
                'value' => $value
            ];
        }

        return $answers;
    }

    private function mockMindboxApiClient(): void
    {
        $this->mock(
            MindboxApiClientContract::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('send');
                $mock->shouldReceive('clientSurvey');
            }
        );
    }
}
