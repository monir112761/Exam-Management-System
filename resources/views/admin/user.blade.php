@extends('layouts.admin')

@section('title','Users')
@section('page-title','Users Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-0">Users Management</h3>
        <small class="text-muted">Dashboard / Users</small>
    </div>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <form method="GET" action="{{ route('admin.users') }}">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div class="d-flex gap-2">

                    <input type="text"
                        name="search"
                        class="form-control"
                        style="width:350px"
                        placeholder="Search by Name, Email or Number..."
                        value="{{ request('search') }}">

                    <button class="btn btn-primary">
                        Search
                    </button>

                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        Reset
                    </a>

                </div>

                <div class="d-flex gap-2">
                    <span class="badge bg-primary px-3 py-2" style="font-size:13px;">
                        Total Users : {{ $totalUsers }}
                    </span>

                    <span class="badge bg-success px-3 py-2" style="font-size:13px;">
                        Filter Users : {{ $filterUsers }}
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
                        <th>Name</th>
                        <th>Email</th>
                        <th width="140">Mobile</th>
                        <th width="180">Registered On</th>
                        <th width="100" class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($users as $user)

                <tr>

                    <td style="color: green">#{{ $user->id }}</td>

                    <td>{{ $user->name }}</td>

                    <td>{{ $user->email }}</td>

                    <td>{{ $user->number }}</td>

                    <td>{{ $user->created_at->format('d M Y') }}</td>

                    <td class="text-center">

                        <a href="{{ route('admin.users.delete',$user->id) }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this user?')">

                            <i class="fa-solid fa-trash"></i>

                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center">
                        No Users Found
                    </td>
                </tr>

                @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
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