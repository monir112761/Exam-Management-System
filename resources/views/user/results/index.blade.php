@extends('layouts.user')

@section('title','My Results')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">My Results</h3>
            <small class="text-muted">Your exam result history</small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th width="80">Sr.No</th>
                            <th>Exam</th>
                            <th width="120">Score</th>
                            <th width="180">Date</th>
                            <th width="120" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($results as $result)
                        <tr>    
                            <td style="color:green">
                                {{ $results->total() - ($results->firstItem() + $loop->index - 1) }}
                            </td>
                            <td>{{ $result->exam->title ?? '-' }}</td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $result->score }} / {{ $result->total_questions }}
                                </span>
                            </td>

                            <td>{{ $result->created_at->format('d M Y') }}</td>

                            <td class="text-center">
                                <a href="{{ route('user.results.view',$result->id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No Results Found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
                <div class="mt-3">
                    {{ $results->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
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