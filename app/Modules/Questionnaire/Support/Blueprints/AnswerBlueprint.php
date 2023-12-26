<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Support\Blueprints;

class AnswerBlueprint
{
    public function __construct(
        public readonly string $identifier,
        public readonly string $value,
        public readonly ?string $comment = null
    ) {
    }
}
