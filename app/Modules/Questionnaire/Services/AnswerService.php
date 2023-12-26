<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Services;

use App\Modules\Questionnaire\Models\Answer;
use App\Modules\Questionnaire\Models\Question;
use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Repositories\AnswerRepository;
use App\Modules\Questionnaire\Repositories\QuestionRepository;
use App\Modules\Questionnaire\Repositories\SurveyRepository;
use App\Modules\Questionnaire\Support\Blueprints\AnswerBlueprint;
use App\Modules\Questionnaire\Support\Filters\AnswerFilter;
use Illuminate\Support\Collection;

class AnswerService
{
    public function __construct(
        private readonly AnswerRepository $answerRepository,
        private readonly QuestionRepository $questionRepository
    ) {
    }

    /**
     * @param AnswerFilter $filter
     * @return Collection<Answer>
     */
    public function getAnswers(AnswerFilter $filter): Collection
    {
        return $this->answerRepository->getCollectionByFilter($filter);
    }

    public function createAnswer(AnswerBlueprint $blueprint, Question|string $question): Answer
    {
        if (is_string($question)) {
            $question = $this->questionRepository->getByUuid($question, true);
        }

        return $this->answerRepository->create($blueprint, $question);
    }

    public function deleteAnswer(Answer|string $answer): void
    {
        if (is_string($answer)) {
            $answer = $this->answerRepository->getByUuid($answer, true);
        }
        $this->answerRepository->delete($answer);
    }
}
