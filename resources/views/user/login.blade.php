<!DOCTYPE html>
<html>
<head>
    <title>Exam Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body{
            min-height:100vh;
            background:linear-gradient(135deg,#2563eb,#7c3aed);
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:'Segoe UI',sans-serif;
        }

        .login-card{
            width:420px;
            background:#fff;
            border-radius:22px;
            padding:32px;
            box-shadow:0 20px 45px rgba(0,0,0,.18);
        }

        .login-card h3{
            font-weight:800;
            color:#111827;
            text-align:center;
        }

        .login-card p{
            color:#64748b;
            text-align:center;
            margin-bottom:25px;
        }

        .role-switch{
            display:grid;
            grid-template-columns:repeat(2, minmax(0, 1fr));
            gap:10px;
            margin-bottom:20px;
        }

        .role-option{
            border:1px solid #dbe3f0;
            border-radius:12px;
            padding:12px 14px;
            text-align:center;
            cursor:pointer;
            font-weight:700;
            color:#475569;
            background:#f8fafc;
            transition:all .2s ease;
        }

        .role-option.active{
            background:#2563eb;
            border-color:#2563eb;
            color:#fff;
            box-shadow:0 8px 20px rgba(37,99,235,.2);
        }

        .form-control{
            height:46px;
            border-radius:12px;
        }

        .btn-login{
            height:46px;
            border-radius:12px;
            font-weight:700;
            background:#2563eb;
            border:none;
        }

        .btn-login:hover{
            background:#1d4ed8;
        }

        .register-link a{
            color:#2563eb;
            font-weight:700;
            text-decoration:none;
        }
    </style>
</head>

<body>

<div class="login-card">

    <h3>Welcome Back</h3>
    <p>{{ $selectedRole === 'admin' ? 'Admin login portal' : 'Login to continue your exam' }}</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('user.login.store') }}" method="POST">
        @csrf
        <input type="hidden" name="role" id="login-role" value="{{ $selectedRole ?? 'user' }}">

        <div class="role-switch" aria-label="Select login role">
            <div class="role-option {{ ($selectedRole ?? 'user') === 'user' ? 'active' : '' }}" data-role="user">
                <i class="fa-solid fa-user me-2"></i>User
            </div>
            <div class="role-option {{ ($selectedRole ?? 'user') === 'admin' ? 'active' : '' }}" data-role="admin">
                <i class="fa-solid fa-user-shield me-2"></i>Admin
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Enter your email">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter your password">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                   <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary btn-login w-100">
            Login as {{ ucfirst($selectedRole ?? 'user') }}
        </button>
    </form>

    @if(($selectedRole ?? 'user') !== 'admin')
        <div class="text-center mt-4 register-link">
            Don’t have an account?
            <a href="{{ route('user.register') }}">Create Account</a>
        </div>
    @endif

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

    document.querySelectorAll('.role-option').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('.role-option').forEach(function (option) {
                option.classList.remove('active');
            });

            button.classList.add('active');
            document.getElementById('login-role').value = button.dataset.role;
            var submitButton = document.querySelector('.btn-login');
            if (submitButton) {
                submitButton.textContent = 'Login as ' + button.dataset.role.charAt(0).toUpperCase() + button.dataset.role.slice(1);
            }
        });
    });
</script>

</body>
</html>