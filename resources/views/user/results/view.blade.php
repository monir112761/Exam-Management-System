@extends('layouts.user')

@section('title','View Result')

@section('content')

<style>
.result-page{
    background:#f4f7fb;
    padding:30px 0;
}

.summary-card,
.question-card{
    background:#fff;
    border-radius:16px;
    box-shadow:0 8px 25px rgba(15,23,42,.08);
    border:0;
}

.summary-box{
    padding:18px;
    border-radius:12px;
    background:#f8fafc;
    border:1px solid #e5e7eb;
}

.option-box{
    border:1px solid #dbe3ef;
    padding:14px 16px;
    border-radius:10px;
    margin-bottom:12px;
    font-weight:600;
    background:#fff;
}

.option-correct{
    background:#dcfce7;
    border-color:#22c55e;
}

.option-wrong{
    background:#fee2e2;
    border-color:#ef4444;
}

.option-key{
    display:inline-block;
    min-width:34px;
    text-align:center;
    background:#eff6ff;
    color:#2563eb;
    border:1px solid #93c5fd;
    padding:5px 9px;
    border-radius:7px;
    font-weight:800;
    margin-right:10px;
}

.not-answered-box{
    background:#fff7ed;
    border:1px solid #f97316;
    color:#c2410c;
    padding:12px 16px;
    border-radius:10px;
    font-weight:700;
    margin-top:12px;
}
</style>

<div class="result-page">
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Result Details</h3>
            <small class="text-muted">My Results / View</small>
        </div>

        <a href="{{ route('user.results') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card summary-card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Result Summary</h5>

            <span class="fw-semibold">
                Date : {{ $result->created_at->format('d M Y, h:i A') }}
            </span>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="summary-box">
                        <strong>Exam</strong><br>
                        {{ $result->exam->title ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="summary-box">
                        <strong>Score</strong><br>
                        <span class="badge bg-primary">
                            {{ $result->score }} / {{ $result->total_questions }}
                        </span>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="summary-box">
                        <strong>Total</strong><br>
                        {{ $result->total_questions }}
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="summary-box">
                        <strong>Correct</strong><br>
                        <span class="badge bg-success">
                            {{ $result->correct_answers }}
                        </span>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="summary-box">
                        <strong>Wrong</strong><br>
                        <span class="badge bg-danger">
                            {{ $result->wrong_answers }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3">Question Answers</h5>

    @forelse($answers as $answer)

        @php
            $question = $answer->question;

            $options = [
                'A' => $question->option_a ?? '-',
                'B' => $question->option_b ?? '-',
                'C' => $question->option_c ?? '-',
                'D' => $question->option_d ?? '-',
            ];

            $isNotAnswered = ($answer->user_answer == 'Not Answered' || empty($answer->user_answer));
        @endphp

        <div class="question-card card mb-4">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="fw-bold mb-0">
                        Q{{ $loop->iteration }}. {{ $question->question ?? '-' }}
                    </h5>

                    @if($isNotAnswered)
                        <span class="badge bg-warning text-dark">Not Answered</span>
                    @elseif($answer->is_correct)
                        <span class="badge bg-success">Correct</span>
                    @else
                        <span class="badge bg-danger">Wrong</span>
                    @endif
                </div>

                @foreach($options as $key => $option)

                    @php
                        $isCorrectAnswer = ($question->correct_answer == $key);
                        $isUserAnswer = ($answer->user_answer == $key);

                        $class = '';

                        if($isCorrectAnswer){
                            $class = 'option-correct';
                        } elseif($isUserAnswer){
                            $class = 'option-wrong';
                        }
                    @endphp

                    <div class="option-box {{ $class }}">
                        <span class="option-key">{{ $key }}</span>
                        {{ $option }}

                        @if($isCorrectAnswer)
                            <span class="badge bg-success ms-2">
                                Correct Answer
                            </span>
                        @elseif($isUserAnswer)
                            <span class="badge bg-danger ms-2">
                                Your Answer
                            </span>
                        @endif
                    </div>

                @endforeach

                @if($isNotAnswered)
                    <div class="not-answered-box">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        Not Answered
                    </div>
                @endif

            </div>
        </div>

    @empty

        <div class="card">
            <div class="card-body text-center">
                No Answers Found
            </div>
        </div>

    @endforelse

</div>
</div>

@endsection