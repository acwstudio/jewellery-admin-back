<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Questionnaire\Survey;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'questionnaire_send_survey_data',
    description: 'Отправить опрос',
    type: 'object'
)]
class PublishedSurveyData extends Data
{
    public function __construct(
        #[Property(property: 'rateStore', type: 'int')]
        public readonly int $rateStore,
        #[Property(
            property: 'whatImprove',
            type: 'array',
            items: new Items(type: 'string')
        )]
        public readonly ?array $whatImprove,
        #[Property(property: 'shop', type: 'string')]
        public readonly string $shop,
        #[Property(property: 'phoneNumber', type: 'string')]
        public readonly string $phoneNumber,
        #[Property(property: 'crmId', type: 'string')]
        public readonly string $crmId,
    ) {
    }
}
