<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionExcelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_question_excel_template(): void
    {
        session([
            'admin_logged_in' => true,
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ]);

        $response = $this->get(route('admin.questions.export'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_descriptive_questions_can_be_imported_without_choice_fields(): void
    {
        session([
            'admin_logged_in' => true,
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ]);

        $exam = Exam::create([
            'title' => 'Physics',
            'description' => 'Physics exam',
            'duration' => 30,
            'status' => 'draft',
        ]);

        Question::create([
            'exam_id' => $exam->id,
            'subject_name' => 'Physics',
            'question_type' => 'descriptive',
            'question' => 'Explain Newton\'s first law of motion.',
            'option_a' => null,
            'option_b' => null,
            'option_c' => null,
            'option_d' => null,
            'correct_answer' => null,
            'marks' => 10,
        ]);

        $this->assertDatabaseHas('questions', [
            'question_type' => 'descriptive',
            'exam_id' => $exam->id,
            'marks' => 10,
        ]);
        $this->assertNull(Question::first()->option_a);
        $this->assertNull(Question::first()->correct_answer);
    }
}
