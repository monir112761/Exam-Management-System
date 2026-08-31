@extends('layouts.user')

@section('title','Exam Result')

@section('content')

<style>
.result-page{
    background:#f4f7fb;
    min-height:80vh;
    padding:50px 0;
    overflow:hidden;
}

.result-card{
    max-width:750px;
    margin:auto;
    overflow:hidden;
    background:#fff;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    overflow:hidden;
}

.result-header{
    background:linear-gradient(135deg,#2563eb,#4f46e5);
    color:#fff;
    text-align:center;
    padding:35px;
}

.result-header i{
    font-size:70px;
    margin-bottom:15px;
    color:#22c55e;
}

.result-body{
    padding:35px;
    overflow: hidden;
}

.stat-box{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:20px;
    text-align:center;
}

.stat-box h4{
    margin:0;
    font-weight:700;
}

.score-circle{
    width:140px;
    height:140px;
    margin:25px auto;
    border-radius:50%;
    background:#2563eb;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;
}

.score-circle h2{
    margin:0;
    font-size:38px;
    font-weight:800;
}

.btn-result{
    padding:12px 35px;
    font-size:17px;
    border-radius:10px;
}
</style>


<div class="container">

<div class="result-card" style="position: relative; bottom: 22px">

    <div class="result-header">

        <i class="fa-solid fa-circle-check"></i>

        <h2 class="fw-bold mb-2">
            Exam Submitted Successfully
        </h2>

        <p class="mb-0">
            {{ $exam->title }}
        </p>

    </div>

    <div class="result-body">

        <div class="score-circle">
            <small>Score</small>
            <h2>{{ $score }}</h2>
            <small>/ {{ $total }}</small>
        </div>

        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="stat-box">
                    <small>Total Questions</small>
                    <h4>{{ $total }}</h4>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <small class="text-success">Correct Answers</small>
                    <h4 class="text-success">{{ $correct }}</h4>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <small class="text-danger">Wrong Answers</small>
                    <h4 class="text-danger">{{ $wrong }}</h4>
                </div>
            </div>

        </div>

        <div class="text-center">

            <a href="{{ route('user.results.view',$result->id) }}"
               class="btn btn-primary btn-result">
                <i class="fa-solid fa-eye"></i>
                View Full Result
            </a>

        </div>

    </div>

</div>

</div>


@endsection