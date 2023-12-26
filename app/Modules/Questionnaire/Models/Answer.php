<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\Models;

use Database\Factories\Modules\Questionnaire\AnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property string $question_uuid
 * @property string $identifier
 * @property string $value
 * @property string|null $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Question $question
 *
 * @method static self|null find(string $uuid)
 * @method static self findOrFail(string $uuid)
 */
class Answer extends AbstractModel
{
    use HasFactory;

    protected $table = 'questionnaire.answers';
    protected $fillable = ['identifier', 'value', 'comment'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_uuid');
    }

    protected static function newFactory()
    {
        return app(AnswerFactory::class);
    }
}
