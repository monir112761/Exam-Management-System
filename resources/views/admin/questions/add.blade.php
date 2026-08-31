@extends('layouts.admin')

@section('title','Add Question')
@section('page-title','Add Question')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-0">Add Question</h3>
        <small class="text-muted">Dashboard / Questions / Add</small>
    </div>

    <a href="{{ route('admin.questions') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.questions.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Subject Name</label>
                    <input type="text"
                           name="subject_name"
                           value="{{ old('subject_name') }}"
                           class="form-control @error('subject_name') is-invalid @enderror"
                           placeholder="e.g. Mathematics, Physics">
                    @error('subject_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Question Type</label>
                    <select name="question_type"
                            class="form-select @error('question_type') is-invalid @enderror">
                        <option value="mcq" {{ old('question_type', 'mcq') == 'mcq' ? 'selected' : '' }}>MCQ</option>
                        <option value="single_choice" {{ old('question_type') == 'single_choice' ? 'selected' : '' }}>Single Choice</option>
                        <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>True / False</option>
                        <option value="descriptive" {{ old('question_type') == 'descriptive' ? 'selected' : '' }}>Descriptive</option>
                    </select>
                    @error('question_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Exam --}}

            <div class="mb-3">
                <label class="form-label fw-bold">Exam</label>

                <select name="exam_id"
                        class="form-select @error('exam_id') is-invalid @enderror">

                    <option value="">Select Exam</option>

                    @foreach($exams as $exam)

                        <option value="{{ $exam->id }}"
                            {{ old('exam_id') == $exam->id ? 'selected' : '' }}>

                            {{ $exam->title }}

                        </option>

                    @endforeach

                </select>

                @error('exam_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            {{-- Question --}}

            <div class="mb-3">
                <label class="form-label fw-bold">Question</label>

                <textarea name="question"
                          rows="3"
                          class="form-control @error('question') is-invalid @enderror">{{ old('question') }}</textarea>

                @error('question')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">Option A</label>

                    <input type="text"
                           name="option_a"
                           value="{{ old('option_a') }}"
                           class="form-control @error('option_a') is-invalid @enderror">

                    @error('option_a')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">Option B</label>

                    <input type="text"
                           name="option_b"
                           value="{{ old('option_b') }}"
                           class="form-control @error('option_b') is-invalid @enderror">

                    @error('option_b')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">Option C</label>

                    <input type="text"
                           name="option_c"
                           value="{{ old('option_c') }}"
                           class="form-control @error('option_c') is-invalid @enderror">

                    @error('option_c')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">Option D</label>

                    <input type="text"
                           name="option_d"
                           value="{{ old('option_d') }}"
                           class="form-control @error('option_d') is-invalid @enderror">

                    @error('option_d')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

            </div>

            {{-- Correct Answer --}}

            <div class="mb-3">

                <label class="form-label fw-bold">Correct Answer</label>

                <select name="correct_answer"
                        class="form-select @error('correct_answer') is-invalid @enderror">

                    <option value="">Select Answer</option>

                    <option value="A" {{ old('correct_answer')=='A'?'selected':'' }}>Option A / True</option>
                    <option value="B" {{ old('correct_answer')=='B'?'selected':'' }}>Option B / False</option>
                    <option value="C" {{ old('correct_answer')=='C'?'selected':'' }}>Option C</option>
                    <option value="D" {{ old('correct_answer')=='D'?'selected':'' }}>Option D</option>

                </select>

                @error('correct_answer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Marks</label>
                <input type="number"
                        name="marks"
                        value="{{ old('marks', 1) }}"
                        class="form-control @error('marks') is-invalid @enderror"
                        placeholder="Example: 5">
                @error('marks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i>
                Save Question
            </button>

        </form>

    </div>

</div>

@endsection