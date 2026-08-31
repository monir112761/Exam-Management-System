@extends('layouts.user')

@section('title', 'Pro Enrollment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <span class="badge bg-warning text-dark px-3 py-2 mb-3">Pro Access</span>
                        <h3 class="fw-bold mb-1">Upgrade your account</h3>
                        <p class="text-muted mb-0">Pay in Bangladeshi Taka to unlock premium exam access.</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="alert alert-light border mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Selected plan</span>
                            <strong>{{ $proAccessType->name ?? 'ST-1' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span>Fee</span>
                            <strong>৳ {{ number_format((float) ($proAccessType->fee ?? 500), 2) }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('user.pro.enroll.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount in BDT</label>
                            <input type="number" name="amount" class="form-control form-control-lg" min="1" step="0.01" value="{{ $proAccessType->fee ?? 500 }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Transaction ID / Payment Reference</label>
                            <input type="text" name="transaction_id" class="form-control form-control-lg" placeholder="e.g. BKASH-2026-001" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Confirm Pro Enrollment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
