<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body{
            min-height:100vh;
            background:linear-gradient(135deg,#0f172a,#2563eb);
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:'Segoe UI',sans-serif;
        }

        .login-card{
            width:410px;
            background:#fff;
            padding:35px;
            border-radius:22px;
            box-shadow:0 22px 50px rgba(0,0,0,.25);
        }

        .admin-icon{
            width:70px;
            height:70px;
            background:#2563eb;
            color:#fff;
            border-radius:18px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:32px;
            margin:0 auto 18px;
        }

        .login-card h2{
            text-align:center;
            font-weight:900;
            color:#111827;
            margin-bottom:6px;
        }

        .login-card p{
            text-align:center;
            color:#64748b;
            margin-bottom:25px;
        }

        .form-control{
            height:48px;
            border-radius:12px;
        }

        .btn-login{
            height:48px;
            border-radius:12px;
            font-weight:800;
            background:#2563eb;
            border:none;
        }

        .btn-login:hover{
            background:#1d4ed8;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="admin-icon">
        <i class="fa-solid fa-user-shield"></i>
    </div>

    <h2>Admin Login</h2>
    <p>Access your exam management panel</p>

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

    <form action="{{ route('admin.login.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Enter admin email">

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
                       placeholder="Enter password">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-login w-100">
            Login
        </button>
    </form>

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

</body>
</html>