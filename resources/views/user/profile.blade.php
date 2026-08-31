@extends('layouts.user')

@section('title','My Profile')

@section('content')

<div class="row g-4">

    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-4">

                <div style="width:90px;height:90px;border-radius:50%;background:#2563eb;color:white;display:flex;align-items:center;justify-content:center;font-size:38px;margin:0 auto 20px;">
                    <i class="fa-solid fa-user"></i>
                </div>

                <h3 class="fw-bold">{{ $user->name }}</h3>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                <p class="text-muted mb-1">{{ $user->number }}</p>

                <div class="d-flex justify-content-center flex-wrap gap-2 mt-3 mb-3">
                    <span class="badge bg-primary-subtle text-primary-emphasis px-3 py-2">
                        <i class="fa-solid fa-user-tag me-1"></i>
                        {{ $user->accessType?->name ?? 'FREE' }}
                    </span>
                    <span class="badge {{ $user->hasVerifiedEmail() ? 'bg-success-subtle text-success-emphasis' : 'bg-warning-subtle text-warning-emphasis' }} px-3 py-2">
                        <i class="fa-solid {{ $user->hasVerifiedEmail() ? 'fa-circle-check' : 'fa-circle-exclamation' }} me-1"></i>
                        {{ $user->hasVerifiedEmail() ? 'Verified' : 'Pending Verification' }}
                    </span>
                </div>

                @if($user->city || $user->state || $user->country)
                    <p class="text-muted mb-1">{{ collect([$user->city, $user->state, $user->country])->filter()->implode(', ') }}</p>
                @endif
                @if($user->gender)
                    <p class="text-muted mb-1">{{ ucfirst(str_replace('-', ' ', $user->gender)) }}</p>
                @endif
                @if($user->date_of_birth)
                    <p class="text-muted mb-1">DOB: {{ $user->date_of_birth->format('d M Y') }}</p>
                @endif
                @if($user->bio)
                    <p class="text-muted mb-0">{{ \Illuminate\Support\Str::limit($user->bio, 120) }}</p>
                @endif

                <hr>

                <p class="mb-0">
                    <strong>Registered On:</strong>
                    {{ $user->created_at->format('d M Y') }}
                </p>

            </div>
        </div>
    </div>

    <div class="col-lg-7">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">Update Profile</h4>

                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror">

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror">

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mobile Number</label>
                        <input type="text"
                               name="number"
                               value="{{ old('number', $user->number) }}"
                               maxlength="10"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"
                               class="form-control @error('number') is-invalid @enderror">

                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-control @error('city') is-invalid @enderror">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State</label>
                            <input type="text" name="state" value="{{ old('state', $user->state) }}" class="form-control @error('state') is-invalid @enderror">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" value="{{ old('country', $user->country) }}" class="form-control @error('country') is-invalid @enderror">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select gender</option>
                                @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other', 'prefer-not-to-say' => 'Prefer not to say'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('gender', $user->gender) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" class="form-control @error('date_of_birth') is-invalid @enderror">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-primary mt-4">
                        Update Profile
                    </button>
                </form>

            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">Change Password</h4>

                <form action="{{ route('user.password.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-danger">
                        Change Password
                    </button>
                </form>

            </div>
        </div>

    </div>

</div>

<script>
    function togglePassword(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

@endsection