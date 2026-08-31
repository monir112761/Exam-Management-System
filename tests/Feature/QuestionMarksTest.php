<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionMarksTest extends TestCase
{
    use RefreshDatabase;

    public function test_exam_score_counts_question_marks(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'number' => '0170000000',
            'password' => bcrypt('secret123'),
        ]);

        $exam = Exam::create([
            'title' => 'Math Exam',
            'description' => 'Exam with marks',
            'duration' => 30,
            'status' => 1,
        ]);

        $q1 = Question::create([
            'exam_id' => $exam->id,
            'subject_name' => 'Mathematics',
            'question_type' => 'mcq',
            'question' => '2 + 2 = ?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_answer' => 'B',
            'marks' => 5,
        ]);

        $q2 = Question::create([
            'exam_id' => $exam->id,
            'subject_name' => 'Mathematics',
            'question_type' => 'mcq',
            'question' => '5 + 5 = ?',
            'option_a' => '9',
            'option_b' => '10',
            'option_c' => '11',
            'option_d' => '12',
            'correct_answer' => 'A',
            'marks' => 3,
        ]);

        $response = $this->withSession([
            'user_logged_in' => true,
            'user_id' => $user->id,
            'user_name' => $user->name,
        ])->post(route('exam.submit', $exam->id), [
            'answer' => [
                $q1->id => 'B',
                $q2->id => 'C',
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('results', [
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'score' => 5,
            'correct_answers' => 1,
        ]);
    }
}
