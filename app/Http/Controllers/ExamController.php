<?php

/*
|--------------------------------------------------------------------------
| Developed by: Moniruzzaman Monir
| Email: monir112761@gmail.com
| Website: https://rcit-solution.com
|--------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use App\Models\AccessType;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $totalExams = Exam::count();
        $query = Exam::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('duration', 'like', '%'.$search.'%')
                    ->orWhere('status', 'like', '%'.$search.'%');
            });
        }

        $exams = $query->latest()->paginate(10);
        $filterExams = $request->filled('search') ? $exams->total() : 0;

        return view('admin.exams.index', compact('exams', 'totalExams', 'filterExams'));
    }

    public function create()
    {
        $accessTypes = AccessType::where('is_active', true)->get();
        $questions = Question::orderByDesc('id')->get();

        return view('admin.exams.add', compact('accessTypes', 'questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:draft,scheduled,published,ongoing,completed,cancelled'],
            'scheduled_at' => ['nullable', 'date'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'access_types' => ['nullable', 'array'],
            'question_ids' => ['nullable', 'array'],
            'question_ids.*' => ['integer', 'exists:questions,id'],
            'question_marks' => ['nullable', 'array'],
            'question_marks.*' => ['integer', 'min:1'],
            'manual_question.question' => ['nullable', 'string'],
            'manual_question.subject_name' => ['nullable', 'string', 'max:255'],
            'manual_question.unit_name' => ['nullable', 'string', 'max:255'],
            'manual_question.question_type' => ['nullable', 'in:mcq,single_choice,true_false,descriptive'],
            'manual_question.marks' => ['nullable', 'integer', 'min:1'],
        ]);

        $durationMinutes = (int) ($validated['duration_minutes'] ?? $validated['duration']);
        $validated['duration'] = $durationMinutes;
        $validated['duration_minutes'] = $durationMinutes;

        if (! empty($validated['starts_at']) && ! empty($validated['ends_at'])) {
            $start = now()->parse($validated['starts_at']);
            $end = now()->parse($validated['ends_at']);
            if ($end->lt($start)) {
                return back()->withErrors(['ends_at' => 'End time must be after the start time.'])->withInput();
            }
            $diffMinutes = (int) $start->diffInMinutes($end, false);
            if ($diffMinutes < $durationMinutes) {
                return back()->withErrors(['ends_at' => 'Exam end time must be consistent with the configured duration.'])->withInput();
            }
        }

        $exam = Exam::create($validated);

        if (! empty($validated['access_types'])) {
            $exam->accessTypes()->sync($validated['access_types']);
        }

        $this->syncExamQuestions($exam, $request);

        return redirect()->route('admin.exams')->with('success', 'Exam added successfully');
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $accessTypes = AccessType::where('is_active', true)->get();
        $questions = Question::orderByDesc('id')->get();
        $selectedQuestionIds = $exam->questions()->pluck('questions.id')->all();

        return view('admin.exams.edit', compact('exam', 'accessTypes', 'questions', 'selectedQuestionIds'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:draft,scheduled,published,ongoing,completed,cancelled'],
            'scheduled_at' => ['nullable', 'date'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'access_types' => ['nullable', 'array'],
            'question_ids' => ['nullable', 'array'],
            'question_ids.*' => ['integer', 'exists:questions,id'],
            'question_marks' => ['nullable', 'array'],
            'question_marks.*' => ['integer', 'min:1'],
            'manual_question.question' => ['nullable', 'string'],
            'manual_question.subject_name' => ['nullable', 'string', 'max:255'],
            'manual_question.unit_name' => ['nullable', 'string', 'max:255'],
            'manual_question.question_type' => ['nullable', 'in:mcq,single_choice,true_false,descriptive'],
            'manual_question.marks' => ['nullable', 'integer', 'min:1'],
        ]);

        $durationMinutes = (int) ($validated['duration_minutes'] ?? $validated['duration']);
        $validated['duration'] = $durationMinutes;
        $validated['duration_minutes'] = $durationMinutes;

        $exam = Exam::findOrFail($id);
        $exam->update($validated);

        if (! empty($validated['access_types'])) {
            $exam->accessTypes()->sync($validated['access_types']);
        } else {
            $exam->accessTypes()->detach();
        }

        $this->syncExamQuestions($exam, $request);

        return redirect()->route('admin.exams')->with('success', 'Exam updated successfully');
    }

    private function syncExamQuestions(Exam $exam, Request $request): void
    {
        $selectedQuestionIds = collect($request->input('question_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $manualQuestion = $request->input('manual_question', []);
        if (! empty(trim((string) ($manualQuestion['question'] ?? '')))) {
            $questionType = in_array($manualQuestion['question_type'] ?? 'mcq', ['mcq', 'single_choice', 'true_false', 'descriptive'], true)
                ? $manualQuestion['question_type']
                : 'mcq';

            $subjectName = trim((string) ($manualQuestion['subject_name'] ?? ''));
            $unitName = trim((string) ($manualQuestion['unit_name'] ?? ''));
            if ($subjectName !== '' && $unitName !== '') {
                $subjectName = $subjectName.' / '.$unitName;
            } elseif ($subjectName === '' && $unitName !== '') {
                $subjectName = $unitName;
            } else {
                $subjectName = $subjectName !== '' ? $subjectName : ($exam->title ?? 'General');
            }

            $payload = [
                'exam_id' => $exam->id,
                'subject_name' => $subjectName ?: 'General',
                'question_type' => $questionType,
                'question' => trim((string) $manualQuestion['question']),
                'option_a' => trim((string) ($manualQuestion['option_a'] ?? '')) ?: null,
                'option_b' => trim((string) ($manualQuestion['option_b'] ?? '')) ?: null,
                'option_c' => trim((string) ($manualQuestion['option_c'] ?? '')) ?: null,
                'option_d' => trim((string) ($manualQuestion['option_d'] ?? '')) ?: null,
                'correct_answer' => trim((string) ($manualQuestion['correct_answer'] ?? '')) ?: null,
                'marks' => max(1, (int) ($manualQuestion['marks'] ?? 1)),
            ];

            if ($payload['question_type'] === 'descriptive') {
                $payload['option_a'] = null;
                $payload['option_b'] = null;
                $payload['option_c'] = null;
                $payload['option_d'] = null;
                $payload['correct_answer'] = null;
            }

            $createdQuestion = Question::create($payload);
            $selectedQuestionIds[] = $createdQuestion->id;
        }

        $currentQuestionIds = $exam->questions()->pluck('questions.id')->all();
        foreach ($currentQuestionIds as $currentQuestionId) {
            if (! in_array($currentQuestionId, $selectedQuestionIds, true)) {
                $question = Question::find($currentQuestionId);
                if ($question) {
                    $question->update(['exam_id' => null]);
                }
                ExamQuestion::where('exam_id', $exam->id)->where('question_id', $currentQuestionId)->delete();
            }
        }

        foreach ($selectedQuestionIds as $sortOrder => $questionId) {
            $question = Question::find($questionId);
            if (! $question) {
                continue;
            }

            $marks = max(1, (int) ($request->input('question_marks.'.$questionId, $question->marks ?? 1)));
            $question->update(['exam_id' => $exam->id, 'marks' => $marks]);
            ExamQuestion::updateOrCreate(
                ['exam_id' => $exam->id, 'question_id' => $question->id],
                ['marks' => $marks, 'sort_order' => $sortOrder]
            );
        }
    }

    public function delete($id)
    {
        Exam::findOrFail($id)->delete();

        return back()->with('success', 'Exam deleted successfully');
    }

    public function availableExam()
    {
        $user = User::find(session('user_id'));
        $exams = Exam::whereIn('status', ['published', 'ongoing'])
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->paginate(10);

        if ($user) {
            $exams = $exams->filter(fn ($exam) => $user->canAccessExam($exam));
        }

        return view('user.available-exams', compact('exams'));
    }

    public function start($id)
    {
        $user = User::findOrFail(session('user_id'));
        $exam = Exam::findOrFail($id);

        if (! $exam->isPublished()) {
            abort(403, 'This exam is not available yet.');
        }

        if (! $user->canAccessExam($exam)) {
            abort(403, 'Your access type is not allowed for this exam.');
        }

        if ($exam->starts_at && now()->lt($exam->starts_at)) {
            abort(403, 'This exam has not opened yet.');
        }

        if ($exam->ends_at && now()->gt($exam->ends_at)) {
            abort(403, 'This exam window has expired.');
        }

        if (Result::where('user_id', $user->id)->where('exam_id', $exam->id)->exists()) {
            return redirect()->route('user.results')->with('error', 'You have already submitted this exam.');
        }

        $questions = $exam->questions()->orderBy('id')->get();

        return view('user.start-exam', compact('exam', 'questions'));
    }

    public function submit(Request $request, $id)
    {
        $user = User::findOrFail(session('user_id'));
        $exam = Exam::findOrFail($id);

        if (! $user->canAccessExam($exam)) {
            abort(403, 'Your access type is not allowed for this exam.');
        }

        if (Result::where('user_id', $user->id)->where('exam_id', $exam->id)->exists()) {
            return redirect()->route('user.results')->with('error', 'Duplicate exam submission is not allowed.');
        }

        $questions = $exam->questions()->orderBy('id')->get();
        if ($questions->isEmpty()) {
            return back()->with('error', 'This exam does not have any valid questions assigned.');
        }

        $total = $questions->count();
        $correct = 0;
        $wrong = 0;
        $obtainedMarks = 0;
        $totalMarks = 0;

        foreach ($questions as $question) {
            $mark = (int) ($question->marks ?? 1);
            $totalMarks += $mark;

            $selected = $request->answer[$question->id] ?? null;
            $isCorrect = $selected !== null && strtoupper((string) $selected) === strtoupper((string) $question->correct_answer);

            if ($isCorrect) {
                $correct++;
                $obtainedMarks += $mark;
            } else {
                $wrong++;
            }

            UserAnswer::create([
                'result_id' => 0,
                'user_id' => $user->id,
                'exam_id' => $exam->id,
                'question_id' => $question->id,
                'user_answer' => $selected ?? 'Not Answered',
                'is_correct' => $isCorrect ? 1 : 0,
            ]);
        }

        $percentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
        $passed = $percentage >= 50;

        $result = Result::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'total_questions' => $total,
            'correct_answers' => $correct,
            'wrong_answers' => $wrong,
            'score' => $obtainedMarks,
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'percentage' => $percentage,
            'passed' => $passed,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $answers = UserAnswer::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('question_id', $questions->pluck('id'))
            ->get();

        foreach ($answers as $answer) {
            $answer->update(['result_id' => $result->id]);
        }

        $examAttempt = ExamAttempt::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'started_at' => now()->subMinutes($exam->duration_minutes ?? $exam->duration ?? 0),
            'ended_at' => now(),
            'duration_minutes' => $exam->duration_minutes ?? $exam->duration ?? 0,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        foreach ($questions as $question) {
            $selected = $request->answer[$question->id] ?? null;
            $isCorrect = $selected !== null && strtoupper((string) $selected) === strtoupper((string) $question->correct_answer);
            ExamAnswer::create([
                'exam_attempt_id' => $examAttempt->id,
                'question_id' => $question->id,
                'selected_option' => $selected,
                'is_correct' => $isCorrect,
                'obtained_marks' => $isCorrect ? (int) ($question->marks ?? 1) : 0,
            ]);
        }

        return view('user.result', compact('exam', 'total', 'correct', 'wrong', 'obtainedMarks', 'totalMarks', 'percentage', 'passed', 'result'));
    }

    public function apiIndex()
    {
        return response()->json(Exam::with(['questions', 'accessTypes'])->latest()->paginate(15));
    }

    public function apiShow($id)
    {
        $exam = Exam::with(['questions', 'accessTypes'])->findOrFail($id);

        return response()->json($exam);
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:draft,scheduled,published,ongoing,completed,cancelled'],
        ]);

        $exam = Exam::create($request->only(['title', 'description', 'duration', 'status']));

        return response()->json($exam, 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($request->only(['title', 'description', 'duration', 'status']));

        return response()->json($exam);
    }

    public function apiDestroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['message' => 'Exam deleted']);
    }

    public function apiSchedule(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $request->validate([
            'scheduled_at' => ['required', 'date'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        $exam->update($request->only(['scheduled_at', 'starts_at', 'ends_at', 'duration_minutes']));
        $exam->status = 'scheduled';
        $exam->save();

        return response()->json($exam);
    }

    public function apiPublish($id)
    {
        $exam = Exam::findOrFail($id);
        $questions = $exam->questions()->get();

        if ($questions->isEmpty()) {
            return response()->json(['message' => 'Exam cannot be published without valid questions.'], 422);
        }

        $exam->status = 'published';
        $exam->is_published = true;
        $exam->published_at = now();
        $exam->save();

        return response()->json($exam);
    }

    public function apiAttachQuestion(Request $request, $id)
    {
        $request->validate([
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'marks' => ['required', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $exam = Exam::findOrFail($id);
        $question = Question::findOrFail($request->question_id);
        $question->update(['exam_id' => $exam->id, 'marks' => $request->marks]);

        $assignment = ExamQuestion::updateOrCreate(
            ['exam_id' => $exam->id, 'question_id' => $question->id],
            ['marks' => $request->marks, 'sort_order' => $request->sort_order ?? 0]
        );

        return response()->json($assignment);
    }

    public function apiUpdateQuestionAssignment(Request $request, $id, $questionId)
    {
        $request->validate([
            'marks' => ['required', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $assignment = ExamQuestion::where('exam_id', $id)->where('question_id', $questionId)->firstOrFail();
        $assignment->update($request->only(['marks', 'sort_order']));

        $question = Question::findOrFail($questionId);
        $question->update(['marks' => $request->marks]);

        return response()->json($assignment);
    }

    public function apiDeleteQuestionAssignment($id, $questionId)
    {
        $assignment = ExamQuestion::where('exam_id', $id)->where('question_id', $questionId)->firstOrFail();
        $assignment->delete();

        return response()->json(['message' => 'Question removed from exam']);
    }
}
