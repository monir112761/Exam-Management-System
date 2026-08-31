<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title','User Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f1f5f9;
    padding-top:75px;
    font-family:'Segoe UI',sans-serif;
}

.user-navbar{
    position:fixed;
    top:0;
    left:0;
    right:0;
    height:75px;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 40px;
    box-shadow:0 5px 20px rgba(15,23,42,.08);
    z-index:9999;
}

.user-logo{
    text-decoration:none;
    color:#2563eb;
    font-size:30px;
    font-weight:800;
}

.user-menu{
    display:flex;
    align-items:center;
    gap:35px;
}

.user-menu a{
    text-decoration:none;
    color:#374151;
    font-size:16px;
    font-weight:700;
    transition:.3s;
}

.user-menu a:hover,
.user-menu a.active{
    color:#2563eb;
}

.logout{
    color:#ef4444 !important;
}

.menu-btn{
    display:none;
    font-size:28px;
    cursor:pointer;
    color:#1e293b;
}

.content{
    padding:35px;
}

.dash-card{
    background:#fff;
    border-radius:18px;
    padding:28px;
    border:1px solid #e5e7eb;
    box-shadow:0 10px 25px rgba(15,23,42,.08);
}

.dash-icon{
    width:58px;
    height:58px;
    border-radius:14px;
    background:#2563eb;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    margin-bottom:18px;
}


@media(max-width:768px){

    .user-navbar{
        padding:0 20px;
    }

    .user-logo{
        font-size:22px;
    }

    .menu-btn{
        display:block;
    }

    .user-menu{
        position:absolute;
        top:75px;
        left:0;
        width:100%;
        background:#fff;
        display:none;
        flex-direction:column;
        align-items:flex-start;
        padding:20px;
        gap:18px;
        box-shadow:0 8px 20px rgba(15,23,42,.08);
    }

    .user-menu.show{
        display:flex;
    }

    .content{
        padding:20px;
    }
    
    .user-menu a.active{
        background:#2563eb;
        color:#fff;
        font-weight:600;
        border-radius:8px;
    }

    .user-menu a.active:hover{
        background:#1d4ed8;
    }

}

</style>
</head>
<body>

<nav class="user-navbar">

    <a href="{{ route('user.dashboard') }}" class="user-logo">
        Exam Management
    </a>

    <div class="menu-btn" onclick="toggleMenu()">
        <i class="fa-solid fa-bars"></i>
    </div>

    <div class="user-menu" id="userMenu">
        <a href="{{ route('user.dashboard') }}"
        class="{{ Request::routeIs('user.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('available.exams') }}"
        class="{{ Request::routeIs('available.exams') ? 'active' : '' }}">
            Available Exams
        </a>

        <a href="{{ route('user.results') }}"
        class="{{ Request::routeIs('user.results') ? 'active' : '' }}">
            Results
        </a>

        <a href="{{ route('user.logout') }}" class="logout">
            Logout
        </a>
    </div>

</nav>

<div class="content">
    @yield('content')
</div>


<script>
function toggleMenu(){
    document.getElementById('userMenu').classList.toggle('show');
}
</script>

</body>
</html>