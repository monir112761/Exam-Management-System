@extends('layouts.user')

@section('title','User Dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>
@endif

<h2 class="fw-bold mb-1">Welcome, {{ session('user_name') }}</h2>
<p class="text-muted mb-4">Start your exam and check your results here.</p>

<div class="row g-4">

    <div class="col-md-4">
        <div class="dash-card">
            <div class="dash-icon">
                <i class="fa-solid fa-file-pen"></i>
            </div>
            <h4>Available Exams</h4>
            <p class="text-muted">View and start your active exams.</p>
            <a href="{{ route('available.exams') }}" class="btn btn-primary">
                Start Exam
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dash-card">
            <div class="dash-icon">
                <i class="fa-solid fa-square-poll-vertical"></i>
            </div>
            <h4>My Results</h4>
            <p class="text-muted">Check your previous exam scores.</p>
            <a href="{{ route('user.results') }}" class="btn btn-outline-primary">View Results</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dash-card">
            <div class="dash-icon">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <h4>My Profile</h4>
            <p class="text-muted">View your account information.</p>
            <a href="{{ route('user.profile') }}" class="btn btn-outline-dark">Profile</a>
        </div>
    </div>

</div>

@endsection