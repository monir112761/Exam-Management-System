@extends('layouts.admin')

@section('title','Edit Exam')
@section('page-title','Edit Exam')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Edit Exam</h3>
        <small class="text-muted">Dashboard / Exams / Edit</small>
    </div>

    <a href="{{ route('admin.exams') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('admin.exams.update', $exam->id) }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Exam Title</label>
                    <input type="text" name="title" value="{{ old('title', $exam->title) }}" class="form-control @error('title') is-invalid @enderror">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="draft" {{ old('status', $exam->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ old('status', $exam->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="published" {{ old('status', $exam->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="ongoing" {{ old('status', $exam->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status', $exam->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $exam->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $exam->description) }}</textarea>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Duration (Minutes)</label>
                    <input type="number" name="duration" value="{{ old('duration', $exam->duration) }}" class="form-control @error('duration') is-invalid @enderror">
                    @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $exam->scheduled_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Starts At</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $exam->starts_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ends At</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $exam->ends_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Allowed Access Types</label>
                    <div class="border rounded p-2">
                        @foreach($accessTypes as $accessType)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="access_types[]" value="{{ $accessType->id }}" id="edit-access-{{ $accessType->id }}" {{ $exam->accessTypes->contains($accessType->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit-access-{{ $accessType->id }}">{{ $accessType->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-4 border rounded p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Question Assignment</h5>
                    <span class="badge bg-primary-subtle text-primary-emphasis">Total marks: <strong id="exam-total-marks">{{ $exam->questions()->sum('marks') }}</strong></span>
                </div>

                <div class="mb-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Search by Subject</label>
                            <select id="question-subject-filter" class="form-select">
                                <option value="">All Subjects</option>
                                @foreach($questions->pluck('subject_name')->filter()->unique()->sort() as $subject)
                                    <option value="{{ $subject }}">{{ $subject }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Search by Unit / Topic</label>
                            <input id="question-unit-filter" type="text" class="form-control" placeholder="Type unit or topic name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Manual Search</label>
                            <input id="question-manual-search" type="text" class="form-control" placeholder="Search question text...">
                        </div>
                    </div>

                    <label class="form-label fw-bold">Select Existing Questions</label>
                    @if($questions->isEmpty())
                        <div class="alert alert-light border mb-0">No saved questions yet. Create a new question below or import a question sheet first.</div>
                    @else
                        <div class="list-group" id="existing-question-list">
                            @foreach($questions as $question)
                                <div class="list-group-item question-mark-row d-flex justify-content-between align-items-center gap-3" data-subject="{{ $question->subject_name ?? 'General' }}" data-unit="{{ $question->exam?->title ?? ($question->subject_name ?? 'General') }}" data-search="{{ strtolower($question->question) }} {{ strtolower($question->subject_name ?? '') }} {{ strtolower($question->exam?->title ?? '') }}">
                                    <div class="form-check flex-grow-1 mb-0">
                                        <input class="form-check-input question-checkbox" data-question-checkbox type="checkbox" name="question_ids[]" value="{{ $question->id }}" id="edit-question-{{ $question->id }}" {{ in_array((string) $question->id, old('question_ids', $selectedQuestionIds), true) ? 'checked' : '' }}>
                                        <label class="form-check-label d-block" for="edit-question-{{ $question->id }}">
                                            <span class="fw-semibold">{{ Str::limit($question->question, 120) }}</span>
                                            <small class="d-block text-muted">{{ $question->subject_name ?? 'General' }} • {{ $question->question_type }} • {{ $question->marks }} marks</small>
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="form-label mb-0 small text-muted">Marks</label>
                                        <input type="number" min="1" class="form-control form-control-sm" style="width: 90px" name="question_marks[{{ $question->id }}]" value="{{ old('question_marks.' . $question->id, $question->marks) }}" data-question-mark>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="border rounded p-3 bg-light-subtle">
                    <h6 class="fw-bold mb-3">Create a New Question for This Exam</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Subject</label>
                            <input type="text" name="manual_question[subject_name]" value="{{ old('manual_question.subject_name') }}" class="form-control" placeholder="e.g. Mathematics">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit / Topic</label>
                            <input type="text" name="manual_question[unit_name]" value="{{ old('manual_question.unit_name') }}" class="form-control" placeholder="e.g. Algebra or Unit 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Question Type</label>
                            <select name="manual_question[question_type]" class="form-select">
                                <option value="mcq">MCQ</option>
                                <option value="single_choice">Single Choice</option>
                                <option value="true_false">True / False</option>
                                <option value="descriptive">Descriptive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Marks</label>
                            <input type="number" min="1" name="manual_question[marks]" value="{{ old('manual_question.marks', 1) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Question</label>
                            <textarea name="manual_question[question]" rows="3" class="form-control" placeholder="Type a new question here...">{{ old('manual_question.question') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Option A</label>
                            <input type="text" name="manual_question[option_a]" value="{{ old('manual_question.option_a') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Option B</label>
                            <input type="text" name="manual_question[option_b]" value="{{ old('manual_question.option_b') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Option C</label>
                            <input type="text" name="manual_question[option_c]" value="{{ old('manual_question.option_c') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Option D</label>
                            <input type="text" name="manual_question[option_d]" value="{{ old('manual_question.option_d') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correct Answer</label>
                            <select name="manual_question[correct_answer]" class="form-select">
                                <option value="">Select answer</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-warning mt-4">
                <i class="fa-solid fa-pen"></i> Update Exam
            </button>

        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalEl = document.getElementById('exam-total-marks');
        const inputs = document.querySelectorAll('[data-question-mark]');
        const checkboxes = document.querySelectorAll('[data-question-checkbox]');
        const subjectFilter = document.getElementById('question-subject-filter');
        const unitFilter = document.getElementById('question-unit-filter');
        const manualSearch = document.getElementById('question-manual-search');

        const updateTotal = function () {
            let total = 0;
            checkboxes.forEach(function (checkbox) {
                if (!checkbox.checked) return;

                const row = checkbox.closest('.question-mark-row');
                if (!row) return;

                const markInput = row.querySelector('[data-question-mark]');
                if (!markInput) return;

                const value = Number(markInput.value) || 0;
                total += value;
            });

            if (totalEl) {
                totalEl.textContent = total;
            }
        };

        const updateQuestionFilters = function () {
            const selectedSubject = subjectFilter ? subjectFilter.value.trim().toLowerCase() : '';
            const selectedUnit = unitFilter ? unitFilter.value.trim().toLowerCase() : '';
            const searchTerm = manualSearch ? manualSearch.value.trim().toLowerCase() : '';

            document.querySelectorAll('.question-mark-row').forEach(function (row) {
                const subject = (row.dataset.subject || '').toLowerCase();
                const unit = (row.dataset.unit || '').toLowerCase();
                const searchText = (row.dataset.search || '').toLowerCase();

                const matchesSubject = !selectedSubject || subject.includes(selectedSubject);
                const matchesUnit = !selectedUnit || unit.includes(selectedUnit);
                const matchesSearch = !searchTerm || searchText.includes(searchTerm);

                row.style.display = matchesSubject && matchesUnit && matchesSearch ? '' : 'none';
            });
        };

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateTotal);
        });

        inputs.forEach(function (input) {
            input.addEventListener('input', updateTotal);
        });

        [subjectFilter, unitFilter, manualSearch].forEach(function (element) {
            if (element) {
                element.addEventListener('input', updateQuestionFilters);
                element.addEventListener('change', updateQuestionFilters);
            }
        });

        updateTotal();
        updateQuestionFilters();
    });
</script>

@endsection