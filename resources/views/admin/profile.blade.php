@extends('layouts.admin')

@section('title','Admin Profile')
@section('page-title','Profile')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <div style="width:96px;height:96px;border-radius:50%;background:#2563eb;color:white;display:flex;align-items:center;justify-content:center;font-size:38px;margin:0 auto 20px;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ $admin->name }}</h3>
                <p class="text-muted mb-1">{{ $admin->email }}</p>
                @if($admin->number)
                    <p class="text-muted mb-0">{{ $admin->number }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Edit Profile</h4>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="number" value="{{ old('number', $admin->number) }}" class="form-control @error('number') is-invalid @enderror" maxlength="20">
                        @error('number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Change Password</h4>

                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('current_password', this)"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)"><i class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>

                    <button class="btn btn-danger">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
