@extends('layouts.admin')

@section('title','View Result')
@section('page-title','Result Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-0">Result Details</h3>
        <small class="text-muted">Dashboard / Results / View</small>
    </div>

    <a href="{{ route('admin.results') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Result Summary</h5>

        <span class="fw-semibold">
            Date : {{ $result->created_at->format('d M Y, h:i A') }}
        </span>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 mb-3">
                <strong>User Name :</strong><br>
                {{ $result->user->name ?? '-' }}
            </div>

            <div class="col-md-4 mb-3">
                <strong>Exam :</strong><br>
                {{ $result->exam->title ?? '-' }}
            </div>

            <div class="col-md-4 mb-3">
                <strong>Score :</strong><br>
                <span class="badge bg-primary">
                    {{ $result->score }} / {{ $result->total_questions }}
                </span>
            </div>

            <div class="col-md-4 mb-3">
                <strong>Total Questions :</strong><br>
                {{ $result->total_questions }}
            </div>

            <div class="col-md-4 mb-3">
                <strong>Correct :</strong><br>
                <span class="badge bg-success">
                    {{ $result->correct_answers }}
                </span>
            </div>

            <div class="col-md-4 mb-3">
                <strong>Wrong :</strong><br>
                <span class="badge bg-danger">
                    {{ $result->wrong_answers }}
                </span>
            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h5 class="mb-0">Question Answers</h5>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th width="60">No</th>
                        <th>Question</th>
                        <th width="160">User Answer</th>
                        <th width="160">Correct Answer</th>
                        <th width="120" class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($answers as $answer)

                @php
                    $options = [
                        'A' => $answer->question->option_a ?? '-',
                        'B' => $answer->question->option_b ?? '-',
                        'C' => $answer->question->option_c ?? '-',
                        'D' => $answer->question->option_d ?? '-',
                    ];

                    $userAnswerText = $options[$answer->user_answer] ?? 'Not Answered';
                    $correctAnswerText = $options[$answer->question->correct_answer] ?? '-';
                @endphp

                    <tr>
                        <td style="color: green">#{{ $loop->iteration }}</td>

                        <td>
                            {{ $answer->question->question ?? '-' }}
                        </td>

                        <td>
                            <span class="badge {{ $answer->is_correct ? 'bg-success' : 'bg-danger' }}">
                                {{ $answer->user_answer }}
                            </span>
                            <br>
                            <small>{{ $userAnswerText }}</small>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                {{ $answer->question->correct_answer ?? '-' }}
                            </span>
                            <br>
                            <small>{{ $correctAnswerText }}</small>
                        </td>

                        <td class="text-center">
                            @if($answer->is_correct)
                                <span class="badge bg-success">Correct</span>
                            @else
                                <span class="badge bg-danger">Wrong</span>
                            @endif
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center">
                            No Answers Found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection