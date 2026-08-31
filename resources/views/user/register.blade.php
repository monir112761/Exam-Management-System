<!DOCTYPE html>
<html>
<head>
    <title>User Register</title>
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

        .register-card{
            width:430px;
            background:#fff;
            border-radius:22px;
            padding:25px;
            box-shadow:0 20px 45px rgba(0,0,0,.18);
        }

        .register-card h3{
            font-weight:800;
            color:#111827;
        }

        .register-card p{
            color:#64748b;
            margin-bottom:19px;
        }

        .form-control{
            height:43%;
            border-radius:12px;
        }

        .btn-register{
            height:42px;
            border-radius:12px;
            font-weight:700;
            background:#2563eb;
            border:none;
        }

        .btn-register:hover{
            background:#1d4ed8;
        }

        .login-link a{
            color:#2563eb;
            font-weight:700;
            text-decoration:none;
        }
    </style>
</head>

<body>

<div class="register-card">

    <div class="text-center">
        <h3>Create Account</h3>
        <p>Register to start your online exam</p>
    </div>

    <form action="{{ route('user.register.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Enter your name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Enter your email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Mobile Number</label>
            <input type="text" name="number" value="{{ old('number') }}"
                   maxlength="10"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"
                   class="form-control @error('number') is-invalid @enderror"
                   placeholder="Enter mobile number">
            @error('number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Create password">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary btn-register w-100">
            Register
        </button>
    </form>

    <div class="text-center mt-4 login-link">
        Already have an account?
        <a href="{{ route('user.login') }}">Login</a>
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

</body>
</html>