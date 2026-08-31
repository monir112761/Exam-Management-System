<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $table = 'user_answers';

    protected $fillable = [
        'result_id',
        'user_id',
        'exam_id',
        'question_id',
        'user_answer',
        'is_correct',
    ];

    // Result Relation
    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id');
    }

    // User Relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Exam Relation
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    // Question Relation
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
