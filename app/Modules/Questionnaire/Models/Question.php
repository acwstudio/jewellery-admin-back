<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Models;

use Database\Factories\Modules\Questionnaire\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $uuid
 * @property string $survey_uuid
 * @property string $value
 * @property array $options
 * @property string|null $code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Survey $survey
 * @property \Illuminate\Support\Collection<Answer> $answers
 *
 * @method static self|null find(string $uuid)
 * @method static self findOrFail(string $uuid)
 */
class Question extends AbstractModel
{
    use HasFactory;

    protected $table = 'questionnaire.questions';
    protected $fillable = ['value', 'options', 'code'];
    protected $casts = [
        'options' => 'array'
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_uuid');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    protected static function newFactory()
    {
        return app(QuestionFactory::class);
    }
}
