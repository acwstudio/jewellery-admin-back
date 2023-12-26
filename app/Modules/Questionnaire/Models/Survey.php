<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Models;

use App\Modules\Questionnaire\Enums\SurveyTypeEnum;
use Database\Factories\Modules\Questionnaire\SurveyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $uuid
 * @property SurveyTypeEnum $type
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \Illuminate\Support\Collection<Question> $questions
 *
 * @method static self|null find(string $uuid)
 * @method static self findOrFail(string $uuid)
 */
class Survey extends AbstractModel
{
    use HasFactory;

    protected $table = 'questionnaire.surveys';
    protected $with = ['questions'];
    protected $fillable = ['title'];
    protected $casts = [
        'type' => SurveyTypeEnum::class,
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    protected static function newFactory()
    {
        return app(SurveyFactory::class);
    }
}
