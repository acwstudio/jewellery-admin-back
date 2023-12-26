<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Support\Blueprints;

class QuestionBlueprint
{
    public function __construct(
        public readonly string $value,
        public readonly array $options = [],
        public readonly ?string $code = null,
    ) {
    }
}
