@extends('layouts.admin')

@section('title','Questions')
@section('page-title','Questions Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-0">Questions Management</h3>
        <small class="text-muted">Dashboard / Questions</small>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.questions.export') }}" class="btn btn-success">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>

        <form action="{{ route('admin.questions.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
            @csrf
            <input type="file" name="excel_file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-upload"></i> Import Excel
            </button>
        </form>

        <a href="{{ route('admin.questions.add') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Question
        </a>
    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <form action="{{ route('admin.questions') }}" method="GET">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div class="d-flex gap-2">

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        style="width:350px"
                        placeholder="Search by Exam or Question...">

                    <button class="btn btn-primary">
                        Search
                    </button>

                    <a href="{{ route('admin.questions') }}" class="btn btn-secondary">
                        Reset
                    </a>

                </div>

                <div class="d-flex gap-2">
                    <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                        Total Questions : {{ $totalQuestions }}
                    </span>

                    <span class="badge bg-success px-3 py-2" style="font-size:13px;">
                        Filter Questions : {{ $filterQuestions }}
                    </span>
                </div>

            </div>

        </form>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                <tr>

                    <th width="60">ID</th>
                    <th width="150">Subject</th>
                    <th width="180">Exam</th>
                    <th width="90">Type</th>
                    <th>Question</th>
                    <th width="90">Answer</th>
                    <th width="80">Marks</th>
                    <th width="130" class="text-center">Action</th>

                </tr>
                </thead>

                <tbody>

                @forelse($questions as $question)

                <tr>

                    <td style="color: green">#{{ $question->id }}</td>

                    <td>
                        {{ $question->subject_name ?? '-' }}
                    </td>

                    <td>
                        {{ $question->exam->title ?? '-' }}
                    </td>

                    <td>
                        <span class="badge bg-info text-dark">
                            {{ strtoupper(str_replace('_', ' ', $question->question_type ?? 'mcq')) }}
                        </span>
                    </td>

                    <td>
                        {{ $question->question }}
                    </td>

                    <td>
                        <span class="badge bg-success">
                            {{ $question->correct_answer ?? '-' }}
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-warning text-dark">
                            {{ $question->marks ?? 1 }}
                        </span>
                    </td>

                    <td class="text-center">

                        <a href="{{ route('admin.questions.edit',$question->id) }}"
                           class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <a href="{{ route('admin.questions.delete',$question->id) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this question?')">
                            <i class="fa-solid fa-trash"></i>
                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center">
                        No Questions Found
                    </td>
                </tr>

                @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $questions->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        let link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            let url = link.href;
            
            let tableContainer = document.querySelector('.table-responsive');
            tableContainer.style.opacity = '0.5';
            
            fetch(url)
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                
                tableContainer.innerHTML = doc.querySelector('.table-responsive').innerHTML;
                tableContainer.style.opacity = '1';
                
                window.history.pushState(null, '', url);
            });
        }
    });
});
</script>

@endsection