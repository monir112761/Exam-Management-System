@extends('layouts.admin')

@section('title','Dashboard')
@section('page-title','Admin Dashboard')

@section('content')

<style>

.dashboard-header{
    margin-bottom:35px;
}

.dashboard-header h2{
    font-size:34px;
    font-weight:800;
    color:#111827;
}

.dashboard-header p{
    color:#64748b;
    margin-top:6px;
}

.dashboard-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
    gap:22px;
    align-items:stretch;
}

.dashboard-card{
    background:#fff;
    border-radius:18px;
    padding:26px 20px;
    box-shadow:0 10px 30px rgba(15,23,42,.08);
    border-left:6px solid #2563eb;
    transition:transform .2s ease, box-shadow .2s ease;
    cursor:pointer;
    height:100%;
    min-height:190px;
}

.dashboard-card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(37,99,235,.18);
}

.card-icon{
    width:60px;
    height:60px;
    border-radius:16px;
    background:#eff6ff;
    color:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:26px;
    margin-bottom:20px;
}

.dashboard-card h1{
    font-size:38px;
    font-weight:900;
    color:#111827;
    margin-bottom:6px;
}

.dashboard-card h5{
    font-weight:700;
    color:#64748b;
}

.dashboard-card small{
    color:#94a3b8;
}

.dashboard-link{
    text-decoration:none;
}

@media(max-width:768px){
    .dashboard-grid{
        grid-template-columns:1fr;
    }

    .dashboard-header h2{
        font-size:28px;
    }

    .dashboard-card{
        min-height:170px;
        padding:20px 16px;
    }
}

</style>

<div class="dashboard-header">

    <h2>
        Welcome Back 👋
    </h2>

    <p>
        Here is your Exam Management System Overview
    </p>

</div>

<div class="dashboard-grid">

    <a href="{{ route('admin.users') }}" class="dashboard-link">
        <div class="dashboard-card">
            <div class="card-icon"><i class="fa-solid fa-users"></i></div>
            <h1>{{ $totalUsers }}</h1>
            <h5>Total Users</h5>
            <small>View all registered users</small>
        </div>
    </a>

    <a href="{{ route('admin.exams') }}" class="dashboard-link">
        <div class="dashboard-card">
            <div class="card-icon"><i class="fa-solid fa-file-pen"></i></div>
            <h1>{{ $totalExams }}</h1>
            <h5>Total Exams</h5>
            <small>Manage all exam records</small>
        </div>
    </a>

    <a href="{{ route('admin.questions') }}" class="dashboard-link">
        <div class="dashboard-card">
            <div class="card-icon"><i class="fa-solid fa-circle-question"></i></div>
            <h1>{{ $totalQuestions }}</h1>
            <h5>Total Questions</h5>
            <small>Manage question bank</small>
        </div>
    </a>

    <a href="{{ route('admin.results') }}" class="dashboard-link">
        <div class="dashboard-card">
            <div class="card-icon"><i class="fa-solid fa-chart-column"></i></div>
            <h1>{{ $totalResults }}</h1>
            <h5>Total Results</h5>
            <small>Track published outcomes</small>
        </div>
    </a>

    <a href="{{ route('admin.access-types') }}" class="dashboard-link">
        <div class="dashboard-card">
            <div class="card-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <h1>{{ $totalAccessTypes }}</h1>
            <h5>Access Types</h5>
            <small>Manage FREE and Pro plans</small>
        </div>
    </a>

    <a href="{{ route('admin.exams') }}" class="dashboard-link">
        <div class="dashboard-card">
            <div class="card-icon"><i class="fa-solid fa-toggle-on"></i></div>
            <h1>{{ $publishedExams }}</h1>
            <h5>Published / Ongoing</h5>
            <small>Live exam availability</small>
        </div>
    </a>

</div>

@endsection