@extends('layouts.admin')

@section('title','Results')
@section('page-title','Results Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-0">Results Management</h3>
        <small class="text-muted">Dashboard / Results</small>
    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <form action="{{ route('admin.results') }}" method="GET">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div class="d-flex gap-2">

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        style="width:300px"
                        placeholder="Search by User or Exam...">

                    <input type="date"
                        name="date"
                        value="{{ request('date') }}"
                        class="form-control">

                    <button class="btn btn-primary">
                        Search
                    </button>

                    <a href="{{ route('admin.results') }}"
                    class="btn btn-secondary">
                        Reset
                    </a>

                </div>

                <div class="d-flex gap-2">
                    <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                        Total Results : {{ $totalResults }}
                    </span>

                    <span class="badge bg-success px-3 py-2" style="font-size:13px;">
                        Filter Results : {{ $filterResults }}
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

                    <th width="80">ID</th>
                    <th>User Name</th>
                    <th>Exam</th>
                    <th>Date</th>
                    <th width="130" class="text-center">Action</th>

                </tr>
                </thead>

                <tbody>

                @forelse($results as $result)

                <tr>

                    <td style="color:green">#{{ $result->id }}</td>

                    <td>{{ $result->user->name }}</td>

                    <td>{{ $result->exam->title }}</td>

                    <td>
                        {{ $result->created_at->format('d M Y') }}
                    </td>

                    <td class="text-center">

                        <a href="{{ route('admin.results.view',$result->id) }}"
                           class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="4" class="text-center">
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