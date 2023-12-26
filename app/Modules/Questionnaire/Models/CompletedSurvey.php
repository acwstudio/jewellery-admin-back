<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Models;

use Database\Factories\Modules\Questionnaire\SurveyOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property string $survey_uuid
 * @property string $identifier
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Survey $survey
 *
 * @method static self|null find(string $uuid)
 * @method static self findOrFail(string $uuid)
 */
class CompletedSurvey extends AbstractModel
{
    use HasFactory;

    protected $table = 'questionnaire.completed_surveys';
    protected $with = ['survey'];
    protected $fillable = ['identifier'];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_uuid');
    }

    protected static function newFactory()
    {
        return app(SurveyOrderFactory::class);
    }
}
