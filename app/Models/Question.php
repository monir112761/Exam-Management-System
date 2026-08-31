<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'exam_id',
        'subject_name',
        'question_type',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'marks',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function examQuestion()
    {
        return $this->hasOne(ExamQuestion::class, 'question_id');
    }

    public function effectiveMarks(): int
    {
        $marks = $this->marks;

        if ($this->examQuestion && ! is_null($this->examQuestion->marks)) {
            return (int) $this->examQuestion->marks;
        }

        return (int) ($marks ?? 1);
    }
}
