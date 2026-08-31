<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'results';

    protected $fillable = [
        'user_id',
        'exam_id',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'score',
        'total_marks',
        'obtained_marks',
        'percentage',
        'passed',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
