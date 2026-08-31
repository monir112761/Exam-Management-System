<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamQuestionAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_existing_and_manual_questions_with_marks(): void
    {
        $sourceExam = Exam::create([
            'title' => 'Source Pool Exam',
            'description' => 'Question bank source',
            'duration' => 30,
            'status' => 'draft',
        ]);

        $mathQuestion = Question::create([
            'exam_id' => $sourceExam->id,
            'subject_name' => 'Mathematics',
            'question_type' => 'mcq',
            'question' => 'What is 2 + 2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_answer' => 'B',
            'marks' => 2,
        ]);

        $scienceQuestion = Question::create([
            'exam_id' => $sourceExam->id,
            'subject_name' => 'Science',
            'question_type' => 'mcq',
            'question' => 'Which planet is known as the Red Planet?',
            'option_a' => 'Earth',
            'option_b' => 'Mars',
            'option_c' => 'Jupiter',
            'option_d' => 'Venus',
            'correct_answer' => 'B',
            'marks' => 3,
        ]);

        $response = $this->withSession([
            'admin_logged_in' => true,
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ])->post(route('admin.exams.store'), [
            'title' => 'Sample Exam',
            'description' => 'A test exam',
            'duration' => 60,
            'status' => 'draft',
            'question_ids' => [$mathQuestion->id, $scienceQuestion->id],
            'question_marks' => [
                $mathQuestion->id => 5,
                $scienceQuestion->id => 7,
            ],
            'manual_question' => [
                'question_type' => 'descriptive',
                'marks' => 4,
                'question' => 'Explain the water cycle in brief.',
            ],
        ]);

        $response->assertRedirect(route('admin.exams'));

        $exam = Exam::where('title', 'Sample Exam')->first();
        $this->assertNotNull($exam);
        $this->assertEquals(16, $exam->totalMarks());
        $this->assertCount(3, $exam->questions()->get());
        $this->assertDatabaseHas('questions', ['id' => $mathQuestion->id, 'exam_id' => $exam->id, 'marks' => 5]);
        $this->assertDatabaseHas('questions', ['id' => $scienceQuestion->id, 'exam_id' => $exam->id, 'marks' => 7]);
        $this->assertDatabaseHas('questions', ['exam_id' => $exam->id, 'question' => 'Explain the water cycle in brief.']);
    }
}
