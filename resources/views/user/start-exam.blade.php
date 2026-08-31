@extends('layouts.user')

@section('title','Start Exam')

@section('content')

<style>
.exam-page{
    background:#f4f7fb;
}

.exam-header{
    background:#fff;
    padding:25px;
    border-radius:16px;
    box-shadow:0 8px 25px rgba(15,23,42,.08);
    margin-bottom:25px;
}

.exam-wrapper{
    display:grid;
    grid-template-columns:1fr 320px;
    gap:25px;
}

.question-box,
.timer-box,
.nav-box,
.info-box{
    background:#fff;
    border-radius:16px;
    padding:25px;
    box-shadow:0 8px 25px rgba(15,23,42,.08);
}

.question-badge{
    display:inline-block;
    background:#2563eb;
    color:#fff;
    padding:9px 16px;
    border-radius:8px;
    font-weight:700;
    margin-bottom:22px;
}

.question-title{
    font-size:24px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:25px;
}

.option-label{
    display:flex;
    align-items:center;
    gap:15px;
    border:1px solid #dbe3ef;
    padding:16px;
    border-radius:10px;
    margin-bottom:14px;
    cursor:pointer;
    font-weight:600;
}

.option-label:hover{
    border-color:#2563eb;
    background:#eff6ff;
}

.option-label input{
    width:18px;
    height:18px;
}

.option-key{
    background:#eff6ff;
    color:#2563eb;
    border:1px solid #93c5fd;
    padding:8px 14px;
    border-radius:8px;
    font-weight:800;
}

.timer-circle{
    width:160px;
    height:160px;
    border-radius:50%;
    border:10px solid #2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;
    margin:25px auto 10px;
}

#timer{
    font-size:42px;
    font-weight:900;
    color:#0f172a;
}

.nav-btns{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:10px;
}

.q-nav{
    border:none;
    padding:10px;
    border-radius:8px;
    background:#e5e7eb;
    font-weight:800;
}

.q-nav.active{
    background:#2563eb;
    color:white;
}

.q-nav.done{
    background:#22c55e;
    color:white;
}

.action-row{
    display:flex;
    justify-content:space-between;
    margin-top:25px;
}

@media(max-width:900px){
    .exam-wrapper{
        grid-template-columns:1fr;
    }
}
</style>
<div class="exam-header d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold mb-1">{{ $exam->title }}</h3>
        <p class="text-muted mb-0">{{ $exam->description }}</p>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-3">

        <span class="badge bg-primary px-3 py-2 fs-6">
            <i class="fa-solid fa-list me-1"></i>
            Questions : {{ count($questions) }}
        </span>

        <span class="badge bg-success px-3 py-2 fs-6">
            <i class="fa-solid fa-clock me-1"></i>
            Time Left :
            <span id="examTimer">{{ sprintf('%02d', $exam->duration) }}:00</span>
        </span>

    </div>

</div>

<form id="examForm" action="{{ route('exam.submit',$exam->id) }}" method="POST">
@csrf

<div class="exam-wrapper">

    <div class="question-box">

        @foreach($questions as $index => $question)

        <div class="question-card"
             style="{{ $index == 0 ? '' : 'display:none' }}">

            <span class="question-badge">
                Question {{ $index + 1 }} / {{ count($questions) }}
            </span>

            <h4 class="question-title">
                {{ $question->question }}
            </h4>

            <label class="option-label">
                <input type="radio"
                name="answer[{{ $question->id }}]"
                value="A"
                onchange="enableNext()">
                <span class="option-key">A</span>
                {{ $question->option_a }}
            </label>

            <label class="option-label">
                <input type="radio"
                name="answer[{{ $question->id }}]"
                value="B"
                onchange="enableNext()">               
                <span class="option-key">B</span>
                {{ $question->option_b }}
            </label>

            <label class="option-label">
                <input type="radio"
                name="answer[{{ $question->id }}]"
                value="C"
                onchange="enableNext()">
                <span class="option-key">C</span>
                {{ $question->option_c }}
            </label>

            <label class="option-label">
                <input type="radio"
                name="answer[{{ $question->id }}]"
                value="D"
                onchange="enableNext()">
                <span class="option-key">D</span>
                {{ $question->option_d }}
            </label>

        </div>  

        @endforeach

        <div class="action-row">
            <button type="button" class="btn btn-outline-primary" onclick="nextQuestion()">
                Skip Question
            </button>

            <button
                type="button"
                id="nextBtn"
                class="btn btn-primary"
                onclick="nextQuestion()"
                disabled>
                Next Question
            </button>
        </div>

    </div>

    <div>

        {{-- <div class="timer-box mb-4 text-center">
            <h5 class="fw-bold">
                <i class="fa-solid fa-clock text-primary"></i> Time Left
            </h5>

            <div class="timer-circle">
                <span id="timer">30</span>
                <strong>sec</strong>
            </div>
        </div> --}}

        <div class="nav-box">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-list text-primary"></i> Question Navigator
            </h5>

            <div class="nav-btns">
                @foreach($questions as $index => $question)
                    <button type="button"
                            class="q-nav {{ $index == 0 ? 'active' : '' }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>

    </div>

</div>

</form>

<script>
let cards = document.querySelectorAll('.question-card');
let navBtns = document.querySelectorAll('.q-nav');
let current = 0;

function enableNext() {
    document.getElementById("nextBtn").disabled = false;
}

function nextQuestion(){
    document.getElementById("nextBtn").disabled = true;
    navBtns[current].classList.remove('active');
    navBtns[current].classList.add('done');
    cards[current].style.display = "none";
    current++;

    if(current >= cards.length){
        clearInterval(examInterval); // countdown stop
        document.getElementById("examForm").submit();
        return;
    }

    cards[current].style.display = "block";
    navBtns[current].classList.add('active');
}


let totalSeconds = {{ $exam->duration }} * 60;  
function updateExamTimer() {
    let minutes = Math.floor(totalSeconds / 60);
    let seconds = totalSeconds % 60;
    document.getElementById("examTimer").innerHTML =
        String(minutes).padStart(2, '0') + ":" +
        String(seconds).padStart(2, '0');

    if (totalSeconds <= 0) {
        clearInterval(examInterval);
        alert("Exam Time Over!");
        document.getElementById("examForm").submit();
        return;
    }
    totalSeconds--;
}

updateExamTimer();
let examInterval = setInterval(updateExamTimer, 1000);

</script>

@endsection