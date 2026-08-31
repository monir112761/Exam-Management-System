<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg,#f8fbff,#eef2ff); color:#0f172a; font-family:'Segoe UI',sans-serif; }
        .navbar { background: rgba(15,23,42,.95); }
        .hero { padding: 70px 0 40px; }
        .hero-card { border-radius: 24px; background: linear-gradient(135deg,#0f172a,#1d4ed8); color:#fff; padding: 36px; box-shadow: 0 25px 55px rgba(28, 57, 130, .25); }
        .plan-card { border-radius: 22px; background:#fff; border:1px solid #e2e8f0; padding:25px; box-shadow:0 12px 30px rgba(15,23,42,.08); }
        .exam-card { border-radius: 18px; background:#fff; border:1px solid #e2e8f0; padding:20px; }
        .badge-pro { background:#f59e0b; }
        .btn-primary { background:#2563eb; border:none; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="fa-solid fa-graduation-cap me-2"></i>Exam CMS</a>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-outline-light">Login</a>
            <a href="{{ route('user.register') }}" class="btn btn-light text-primary fw-bold">Register</a>
        </div>
    </div>
</nav>

<div class="container hero">
    <div class="hero-card row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge bg-light text-primary fw-bold mb-3">CMS + Digital Exam Platform</span>
            <h1 class="display-5 fw-bold mb-3">Free exam access. Pro enrollment with secure payment.</h1>
            <p class="mb-4 text-light opacity-75">Students can explore free exam modules, upgrade to a Pro plan in Bangladeshi Taka, and verify their email before account activation.</p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('login') }}" class="btn btn-light text-primary fw-bold">Start Free Exam</a>
                <a href="{{ route('user.register') }}" class="btn btn-outline-light">Create Account</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="plan-card text-dark">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Pro Plan</h5>
                    <span class="badge badge-pro text-white">Popular</span>
                </div>
                <div class="display-6 fw-bold mb-2">৳ {{ number_format((float) ($proAccessType->fee ?? 500), 2) }}</div>
                <p class="text-muted mb-3">Upgrade to unlock premium exam access and advanced features.</p>
                <ul class="list-unstyled small text-muted mb-4">
                    <li><i class="fa-solid fa-check text-success me-2"></i> Advanced exam access</li>
                    <li><i class="fa-solid fa-check text-success me-2"></i> Faster enrollment</li>
                    <li><i class="fa-solid fa-check text-success me-2"></i> Premium support</li>
                </ul>
                <a href="{{ route('user.register') }}" class="btn btn-primary w-100">Enroll Now</a>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 mt-2 mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold mb-0">Access Types & Pro Plans</h3>
            </div>
            <div class="row g-3">
                @foreach($accessTypes as $plan)
                    <div class="col-md-6 col-xl-3">
                        <div class="plan-card h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold mb-0">{{ $plan->name }}</h5>
                                @if($plan->code !== 'FREE')
                                    <span class="badge badge-pro text-white">Pro</span>
                                @else
                                    <span class="badge bg-success-subtle text-success">Free</span>
                                @endif
                            </div>
                            <div class="display-6 fw-bold mb-2">
                                @if($plan->code === 'FREE')
                                    Free
                                @else
                                    ৳ {{ number_format((float) ($plan->fee ?? 0), 2) }}
                                @endif
                            </div>
                            <p class="text-muted small mb-3">{{ $plan->description ?: 'Access level for exam eligibility.' }}</p>
                            <div class="small text-muted">
                                <div><i class="fa-solid fa-circle-check text-success me-2"></i>{{ $plan->code }}</div>
                                <div class="mt-2"><i class="fa-solid fa-circle-check text-success me-2"></i>{{ $plan->is_active ? 'Active' : 'Inactive' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold mb-0">Free Available Exams</h3>
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login to Start</a>
            </div>
            @if($freeExams->isNotEmpty())
                @foreach($freeExams as $exam)
                    <div class="exam-card mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $exam->title }}</h5>
                                <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit($exam->description ?? 'Free exam available for all eligible users.', 120) }}</p>
                                <small class="text-muted">
                                    <i class="fa-regular fa-clock me-1"></i>{{ $exam->duration_minutes ?? $exam->duration ?? 30 }} min
                                    &nbsp;|&nbsp;
                                    <i class="fa-solid fa-layer-group me-1"></i>{{ $exam->status }}
                                </small>
                            </div>
                            <span class="badge bg-success-subtle text-success fw-bold">FREE</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="exam-card text-muted">No free exam is published yet.</div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="plan-card h-100">
                <h5 class="fw-bold mb-3">Why choose Pro?</h5>
                <div class="mb-3">
                    <i class="fa-solid fa-certificate text-primary me-2"></i>
                    Premium exam access
                </div>
                <div class="mb-3">
                    <i class="fa-solid fa-shield-heart text-primary me-2"></i>
                    Better account security
                </div>
                <div class="mb-3">
                    <i class="fa-solid fa-bangladeshi-taka-sign text-primary me-2"></i>
                    Pay in BDT for selected plans
                </div>
                <div class="mb-3">
                    <i class="fa-solid fa-envelope-circle-check text-primary me-2"></i>
                    Email verification required
                </div>
                <a href="{{ route('user.register') }}" class="btn btn-primary w-100 mt-3">Register & Verify</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
