<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title','Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body{
            margin:0;
            background:#f4f7fb;
            font-family:Arial, sans-serif;
        }

        .admin-sidebar{
            width:260px;
            height:100vh;
            position:fixed;
            top:0;
            left:0;
            background:#0f172a;
            padding:25px 18px 90px;
            overflow-y:auto;
            overflow-x:hidden;
            scrollbar-width:thin;
            scrollbar-color:#475569 #0f172a;
        }

        .admin-sidebar::-webkit-scrollbar {
            width:8px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background:#0f172a;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background:#475569;
            border-radius:10px;
        }

        .brand-box{
            background:#1e293b;
            color:white;
            padding:18px;
            border-radius:18px;
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:35px;
        }

        .brand-box:hover{
            color:white;
        }

        .brand-icon{
            width:42px;
            height:42px;
            border-radius:50%;
            background:#2563eb;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:20px;
        }

        .brand-box h4{
            margin:0;
            font-size:20px;
            font-weight:800;
        }

        .menu-title{
            color:#64748b;
            font-size:12px;
            font-weight:700;
            margin:20px 12px 10px;
            text-transform:uppercase;
        }

        .admin-sidebar a{
            color:#cbd5e1;
            text-decoration:none;
            display:flex;
            align-items:center;
            gap:14px;
            padding:14px 16px;
            border-radius:14px;
            margin-bottom:8px;
            font-size:16px;
            font-weight:600;
        }

        .admin-sidebar a:hover,
        .admin-sidebar a.active{
            background:#2563eb;
            color:white;
        }

        .admin-sidebar a i{
            width:22px;
            text-align:center;
        }

        .logout-link{
            position:absolute;
            bottom:25px;
            left:18px;
            right:18px;
        }

        .logout-link a{
            color:#f87171;
            background:#1e293b;
        }

        .admin-main{
            margin-left:260px;
            min-height:100vh;
        }

        .topbar{
            height:78px;
            background:white;
            padding:0 35px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            border-bottom:1px solid #e5e7eb;
        }

        .topbar h4{
            margin:0;
            font-weight:800;
            color:#111827;
        }

        .admin-user{
            background:#f1f5f9;
            padding:10px 18px;
            border-radius:30px;
            font-weight:700;
            color:#334155;
        }

        .admin-user i{
            color:#2563eb;
            margin-right:8px;
        }

        .admin-content{
            padding:35px;
        }

        .dash-card{
            background:white;
            border-radius:18px;
            padding:25px;
            box-shadow:0 10px 25px rgba(15,23,42,.08);
            border-left:5px solid #2563eb;
        }

        .dash-card h3{
            font-size:34px;
            font-weight:800;
            margin:0;
            color:#111827;
        }

        .dash-card p{
            margin:8px 0 0;
            color:#64748b;
            font-weight:600;
        }

        @media(max-width:900px){
            .admin-sidebar{
                width:220px;
            }

            .admin-main{
                margin-left:220px;
            }
        }
    </style>
</head>
<body>

<div class="admin-sidebar">

    <a href="{{ route('admin.dashboard') }}" class="brand-box">
        <div class="brand-icon">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <h4>Exam Admin</h4>
    </a>

    <div class="menu-title">Main Menu</div>

    <a href="{{ route('admin.dashboard') }}" 
        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i>
        Dashboard
    </a>

    <a href="{{ route('admin.users') }}"
        class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i>
        Users
    </a>

    <a href="{{ route('admin.exams') }}"
        class="{{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
        <i class="fa-solid fa-file-pen"></i>
        Exams
    </a>

    <a href="{{ route('admin.questions') }}" 
        class="{{ request()->routeIs('admin.questions*') ? 'active' : '' }}">
        <i class="fa-solid fa-circle-question"></i>
        Questions
    </a>

    <a href="{{ route('admin.results') }}"
        class="{{ request()->routeIs('admin.results') ? 'active' : '' }}">
        <i class="fa-solid fa-chart-simple"></i>
        Results
    </a>

    <a href="{{ route('admin.access-types') }}"
        class="{{ request()->routeIs('admin.access-types*') ? 'active' : '' }}">
        <i class="fa-solid fa-id-card"></i>
        Access Types
    </a>

    <a href="{{ route('admin.roles') }}"
        class="{{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
        <i class="fa-solid fa-user-tag"></i>
        Roles
    </a>

    <a href="{{ route('admin.permissions') }}"
        class="{{ request()->routeIs('admin.permissions*') ? 'active' : '' }}">
        <i class="fa-solid fa-shield-halved"></i>
        Permissions
    </a>

    <div class="menu-title">Account</div>

    <a href="{{ route('admin.profile') }}"
        class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
        <i class="fa-solid fa-user-gear"></i>
        Profile
    </a>

    <div class="logout-link">
        <a href="{{ route('admin.logout') }}">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </div>

</div>

<div class="admin-main">

    <div class="topbar">
        <h4>@yield('page-title','Dashboard')</h4>

        <div class="admin-user">
            <i class="fa-solid fa-user-circle"></i>
            {{ session('admin_name') }}
        </div>
    </div>

    <div class="admin-content">
        @yield('content')
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: "{{ session('success') }}",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: "{{ session('error') }}",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});
</script>
@endif


</body>
</html>