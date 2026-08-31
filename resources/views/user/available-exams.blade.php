@extends('layouts.user')

@section('title','Available Exams')

@section('content')

<h2 class="fw-bold mb-4">Available Exams</h2>

<div class="row">
    @forelse($exams as $exam)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h4 class="fw-bold">{{ $exam->title }}</h4>
                    <p class="text-muted">{{ $exam->description }}</p>
                    <p><strong>Duration:</strong> {{ $exam->duration_minutes ?? $exam->duration }} Minutes</p>
                    <p><strong>Total Marks:</strong> {{ $exam->questions()->sum('marks') }}</p>
                    <p><strong>Starts:</strong> {{ $exam->starts_at ? $exam->starts_at->format('d M Y H:i') : 'Any time after publish' }}</p>
                    <p><strong>Access:</strong> {{ $exam->accessTypes->pluck('name')->implode(', ') ?: 'All access types' }}</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('exam.start',$exam->id) }}" class="btn btn-primary w-100">Start Exam</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning">No Active Exam Available.</div>
        </div>
    @endforelse
</div>

@endsection