@extends('layouts.admin')

@section('title','Exams')
@section('page-title','Exam Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-0">Exam Management</h3>
        <small class="text-muted">Dashboard / Exams</small>
    </div>

    <a href="{{ route('admin.exams.add') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Exam
    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <form action="{{ route('admin.exams') }}" method="GET">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div class="d-flex gap-2">

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        style="width:350px"
                        placeholder="Search by Title or Duration...">

                    <button class="btn btn-primary">
                        Search
                    </button>

                    <a href="{{ route('admin.exams') }}" class="btn btn-secondary">
                        Reset
                    </a>

                </div>

                <div class="d-flex gap-2">

                    <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                        Total Exams : {{ $totalExams }}
                    </span>

                    <span class="badge bg-success px-3 py-2" style="font-size:13px;">
                        Filter Exams : {{ $filterExams }}
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
                        <th width="70">ID</th>
                        <th>Exam Title</th>
                        <th>Description</th>
                        <th width="120">Duration</th>
                        <th width="120">Status</th>
                        <th width="140" class="text-center">Action</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($exams as $exam)

                <tr>

                    <td style="color: green">#{{ $exam->id }}</td>

                    <td>
                        <strong>{{ $exam->title }}</strong>
                    </td>

                    <td>
                        {{ $exam->description ?? '-' }}
                    </td>

                    <td>
                        {{ $exam->duration }} Min
                    </td>

                    <td>
                        @php
                            $status = strtolower((string) ($exam->status ?? 'draft'));
                            $badgeClass = match($status) {
                                'published', 'ongoing' => 'bg-success',
                                'scheduled' => 'bg-info',
                                'completed' => 'bg-primary',
                                'cancelled' => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </td>

                    <td class="text-center">

                        <a href="{{ route('admin.exams.edit',$exam->id) }}"
                           class="btn btn-warning btn-sm">

                            <i class="fa-solid fa-pen"></i>

                        </a>

                        <a href="{{ route('admin.exams.delete',$exam->id) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this exam?')">

                            <i class="fa-solid fa-trash"></i>

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center text-muted">
                        No Exams Found
                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $exams->withQueryString()->links('pagination::bootstrap-5') }}
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