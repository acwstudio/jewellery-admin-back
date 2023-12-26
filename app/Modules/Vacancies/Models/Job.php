<?php

declare(strict_types=1);

namespace App\Modules\Vacancies\Models;

use Database\Factories\Modules\Vacancies\JobFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $title
 * @property string $salary
 * @property string $city
 * @property string $experience
 * @property string $description
 * @property string $slug
 * @property Department $department
 */
class Job extends Model
{
    use HasFactory;

    protected $table = 'vacancies.jobs';
    protected $fillable = ['title', 'salary', 'city', 'experience', 'description', 'department_id', 'slug'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    protected static function newFactory()
    {
        return app(JobFactory::class);
    }
}
