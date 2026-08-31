<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $table = 'exams';

    protected $fillable = [
        'title',
        'description',
        'duration',
        'duration_minutes',
        'status',
        'scheduled_at',
        'starts_at',
        'ends_at',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'exam_id');
    }

    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class, 'exam_id');
    }

    public function accessTypes(): BelongsToMany
    {
        return $this->belongsToMany(AccessType::class, 'exam_access_types');
    }

    public function totalMarks(): int
    {
        return (int) $this->questions()->sum('marks');
    }

    public function isPublished(): bool
    {
        return in_array(strtolower((string) $this->status), ['published', 'ongoing', 'completed'], true)
            || (bool) $this->is_published;
    }

    public function isAvailableToUser(?User $user = null): bool
    {
        if ($user && $user->hasRole('Super Admin')) {
            return true;
        }

        if (! $this->isPublished()) {
            return false;
        }

        if (! $user) {
            return true;
        }

        return $user->canAccessExam($this);
    }
}
