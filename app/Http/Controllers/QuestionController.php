<?php

/*
|--------------------------------------------------------------------------
| Developed by: Moniruzzaman Monir
| Email: monir112761@gmail.com
| Website: https://rcit-solution.com
|--------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $totalQuestions = Question::count();
        $query = Question::join('exams', 'questions.exam_id', '=', 'exams.id')
            ->select('questions.*');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('question', 'like', '%'.$request->search.'%')
                    ->orWhere('exams.title', 'like', '%'.$request->search.'%')
                    ->orWhere('questions.subject_name', 'like', '%'.$request->search.'%');
            });
        }

        $questions = $query->latest()->paginate(10);
        $filterQuestions = $request->filled('search') ? $questions->total() : 0;

        return view('admin.questions.index', compact('questions', 'totalQuestions', 'filterQuestions'));
    }

    public function create()
    {
        $exams = Exam::whereIn('status', ['draft', 'scheduled', 'published', 'ongoing'])->get();

        return view('admin.questions.add', compact('exams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_name' => ['nullable', 'string', 'max:255'],
            'question_type' => ['required', 'in:mcq,single_choice,true_false,descriptive'],
            'question' => ['required', 'string'],
            'option_a' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'option_b' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'option_c' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'option_d' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'correct_answer' => ['required_if:question_type,mcq,single_choice,true_false', 'string'],
            'marks' => ['required', 'integer', 'min:1'],
        ]);

        if (empty($validated['subject_name'])) {
            $validated['subject_name'] = Exam::find($validated['exam_id'])?->title ?? 'General';
        }

        Question::create($validated);

        return redirect()->route('admin.questions')->with('success', 'Question added successfully');
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $exams = Exam::whereIn('status', ['draft', 'scheduled', 'published', 'ongoing'])->get();

        return view('admin.questions.edit', compact('question', 'exams'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'subject_name' => ['nullable', 'string', 'max:255'],
            'question_type' => ['required', 'in:mcq,single_choice,true_false,descriptive'],
            'question' => ['required', 'string'],
            'option_a' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'option_b' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'option_c' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'option_d' => ['nullable', 'required_if:question_type,mcq,single_choice,true_false', 'string'],
            'correct_answer' => ['required_if:question_type,mcq,single_choice,true_false', 'string'],
            'marks' => ['required', 'integer', 'min:1'],
        ]);

        if (empty($validated['subject_name'])) {
            $validated['subject_name'] = Exam::find($validated['exam_id'])?->title ?? 'General';
        }

        $question = Question::findOrFail($id);
        $question->update($validated);

        return redirect()->route('admin.questions')->with('success', 'Question updated successfully');
    }

    public function delete($id)
    {
        Question::findOrFail($id)->delete();

        return back()->with('success', 'Question deleted successfully');
    }

    public function exportTemplate()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'subject_name',
            'question_type',
            'exam_title',
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_answer',
            'marks',
        ];

        $sheet->fromArray([$headers], null, 'A1');
        $sheet->fromArray([
            ['Computer Science', 'mcq', 'Web Development', 'Which HTML tag is used to create a hyperlink?', '<a>', '<link>', '<href>', '<url>', 'A', 5],
            ['Mathematics', 'single_choice', 'Basic Algebra', 'What is 2 + 2?', '3', '4', '5', '6', 'B', 3],
            ['English', 'true_false', 'Grammar', 'A sentence must end with a full stop.', 'True', 'False', '', '', 'A', 2],
            ['Physics', 'descriptive', 'Mechanics', 'Explain Newton\'s first law of motion.', '', '', '', '', 'Explain clearly.', 10],
        ], null, 'A2');

        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->setTitle('Question Import');

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="question-import-template.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) < 2) {
            return back()->with('error', 'The Excel file is empty or missing data rows.');
        }

        $headerMap = [];
        foreach ($rows[0] as $index => $header) {
            $headerMap[strtolower(trim((string) $header))] = $index;
        }

        $imported = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (empty(array_filter($row, fn ($value) => $value !== null && $value !== ''))) {
                continue;
            }

            $subjectName = trim((string) ($row[$headerMap['subject_name']] ?? ''));
            $examTitle = trim((string) ($row[$headerMap['exam_title']] ?? ''));
            $examTitle = $examTitle !== '' ? $examTitle : ($subjectName !== '' ? $subjectName : 'General');
            $subjectName = $subjectName !== '' ? $subjectName : $examTitle;

            $questionType = strtolower(trim((string) ($row[$headerMap['question_type']] ?? 'mcq')));
            $questionType = in_array($questionType, ['mcq', 'single_choice', 'true_false', 'descriptive'], true)
                ? $questionType
                : 'mcq';

            $exam = Exam::firstOrCreate([
                'title' => $examTitle,
            ], [
                'description' => $examTitle,
                'duration' => 30,
                'status' => 'draft',
            ]);

            $questionText = trim((string) ($row[$headerMap['question']] ?? ''));
            if ($questionText === '') {
                continue;
            }

            $payload = [
                'exam_id' => $exam->id,
                'subject_name' => $subjectName,
                'question_type' => $questionType,
                'question' => $questionText,
                'option_a' => trim((string) ($row[$headerMap['option_a']] ?? '')),
                'option_b' => trim((string) ($row[$headerMap['option_b']] ?? '')),
                'option_c' => trim((string) ($row[$headerMap['option_c']] ?? '')),
                'option_d' => trim((string) ($row[$headerMap['option_d']] ?? '')),
                'correct_answer' => trim((string) ($row[$headerMap['correct_answer']] ?? '')),
                'marks' => max(1, (int) ($row[$headerMap['marks']] ?? 1)),
            ];

            $payload['correct_answer'] = $payload['correct_answer'] !== '' ? strtoupper($payload['correct_answer']) : 'A';

            if (in_array($questionType, ['mcq', 'single_choice', 'true_false'], true) && $payload['correct_answer'] === '') {
                $payload['correct_answer'] = 'A';
            }

            if ($questionType === 'descriptive') {
                $payload['option_a'] = null;
                $payload['option_b'] = null;
                $payload['option_c'] = null;
                $payload['option_d'] = null;
                $payload['correct_answer'] = null;
            }

            Question::create($payload);
            $imported++;
        }

        return back()->with('success', $imported.' questions imported successfully from Excel.');
    }

    public function apiIndex()
    {
        return response()->json(Question::with('exam')->latest()->paginate(15));
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'question' => ['required', 'string'],
            'question_type' => ['required', 'in:mcq,single_choice,true_false,descriptive'],
            'marks' => ['required', 'integer', 'min:1'],
        ]);

        $question = Question::create($validated);

        return response()->json($question, 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->only(['question', 'question_type', 'marks', 'exam_id']));

        return response()->json($question);
    }

    public function apiDestroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Question deleted']);
    }
}
