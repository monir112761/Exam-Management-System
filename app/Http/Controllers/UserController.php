<?php

/*
|--------------------------------------------------------------------------
| Developed by: Moniruzzaman Monir
| Email: monir112761@gmail.com
| Website: https://rcit-solution.com
|--------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use App\Models\AccessType;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected function ensureDefaultAdminAccount(): ?Admin
    {
        $admin = Admin::query()->first();

        if ($admin) {
            return $admin;
        }

        return Admin::create([
            'name' => 'System Admin',
            'email' => 'admin@exam.com',
            'number' => '0190000000',
            'password' => Hash::make('password123'),
        ]);
    }

    // Login Page
    public function login()
    {
        $role = request()->query('role', session('auth_role', 'user'));

        if ($role === 'admin' && session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        if ($role !== 'admin' && session()->has('user_logged_in')) {
            return redirect()->route('user.dashboard');
        }

        return view('user.login', ['selectedRole' => in_array($role, ['admin', 'user'], true) ? $role : 'user']);
    }

    public function home()
    {
        $freeExams = \App\Models\Exam::query()
            ->whereIn('status', ['published', 'ongoing'])
            ->latest()
            ->take(6)
            ->get();

        $accessTypes = AccessType::where('is_active', true)
            ->orderByRaw('CASE WHEN code = "FREE" THEN 0 ELSE 1 END')
            ->orderBy('fee', 'asc')
            ->get();

        $proAccessType = $accessTypes->first(fn ($type) => in_array($type->code, ['ST-1', 'SR-2', 'ST-3', 'PRO'], true));

        if ($proAccessType && (float) $proAccessType->fee <= 0) {
            $proAccessType->update(['fee' => 500]);
        }

        foreach ($accessTypes as $accessType) {
            if ((float) ($accessType->fee ?? 0) <= 0 && $accessType->code !== 'FREE') {
                $accessType->update(['fee' => 500]);
            }
        }

        return view('user.home', compact('freeExams', 'proAccessType', 'accessTypes'));
    }

    public function login_store(Request $request)
    {
        $role = $request->input('role', 'user');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:user,admin',
        ]);

        $account = null;
        $redirectRoute = 'user.dashboard';

        if ($role === 'admin') {
            $this->ensureDefaultAdminAccount();
            $account = Admin::where('email', $request->email)->first();
            if (! $account) {
                $account = Admin::where('email', 'admin@exam.com')->first();
            }
            $redirectRoute = 'admin.dashboard';
        } else {
            $account = User::where('email', $request->email)->first();
        }

        if (! $account || ! Hash::check($request->password, $account->password)) {
            return back()->with('error', 'Invalid Email or Password')->withInput();
        }

        if ($role !== 'admin' && ! empty($account->email_verification_token) && ! $account->hasVerifiedEmail()) {
            return back()->with('error', 'Please verify your email before logging in.')->withInput();
        }

        session()->forget(['user_id', 'user_name', 'user_logged_in', 'admin_id', 'admin_name', 'admin_logged_in', 'auth_role']);

        if ($role === 'admin') {
            session([
                'admin_id' => $account->id,
                'admin_name' => $account->name,
                'admin_logged_in' => true,
                'auth_role' => 'admin',
            ]);

            return redirect()->route($redirectRoute)
                ->with('success', 'Admin login successful. Welcome '.$account->name.'!');
        }

        $defaultAccessType = AccessType::where('code', 'FREE')->first();
        if (! $account->access_type_id && $defaultAccessType) {
            $account->access_type_id = $defaultAccessType->id;
            $account->save();
        }

        session([
            'user_id' => $account->id,
            'user_name' => $account->name,
            'user_logged_in' => true,
            'auth_role' => 'user',
        ]);

        return redirect()->route($redirectRoute)
            ->with('success', 'Login successful. Welcome '.$account->name.'!');
    }

    // Register Page
    public function register()
    {
        if (session()->has('user_logged_in')) {
            return redirect()->route('user.dashboard');
        }

        return view('user.register');
    }

    // Register
    public function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'number' => 'required|digits:10|unique:users,number',
            'password' => 'required|min:6',
        ]);

        $defaultAccessType = AccessType::where('code', 'FREE')->first();
        $verificationToken = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'password' => Hash::make($request->password),
            'access_type_id' => $defaultAccessType?->id,
            'email_verification_token' => $verificationToken,
            'email_verified_at' => null,
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'Student'], ['label' => 'Student', 'description' => 'Default user role', 'is_active' => true]);
        $user->roles()->syncWithoutDetaching([$studentRole->id]);

        Mail::raw('Verify your email by visiting: '.route('user.verify-email', ['token' => $verificationToken]), function ($message) use ($user) {
            $message->to($user->email)->subject('Verify your email address');
        });

        return redirect()->route('user.login')
            ->with('success', 'Registration successful. Please check your email and verify your account before login.');
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (! $user) {
            return redirect()->route('user.login')->with('error', 'Invalid or expired verification link.');
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        return redirect()->route('user.login')->with('success', 'Email verified successfully. You can now login.');
    }

    public function proEnroll()
    {
        $user = User::findOrFail(session('user_id'));
        $proAccessType = AccessType::whereIn('code', ['ST-1', 'PRO'])->first();
        if ($proAccessType && (float) $proAccessType->fee <= 0) {
            $proAccessType->update(['fee' => 500]);
        }

        return view('user.pro-enroll', compact('user', 'proAccessType'));
    }

    public function proEnrollStore(Request $request)
    {
        $user = User::findOrFail(session('user_id'));
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_id' => ['required', 'string', 'max:255'],
        ]);

        $proAccessType = AccessType::whereIn('code', ['ST-1', 'PRO'])->first();
        if (! $proAccessType) {
            $proAccessType = AccessType::firstOrCreate([
                'code' => 'ST-1',
            ], [
                'name' => 'ST-1',
                'description' => 'Pro access plan',
                'fee' => 500,
                'is_active' => true,
            ]);
        }
        if ((float) ($proAccessType->fee ?? 0) <= 0) {
            $proAccessType->update(['fee' => 500]);
        }

        $minimumFee = (float) ($proAccessType->fee ?? 500);
        if ((float) $request->amount < $minimumFee) {
            return back()->with('error', 'Pro enrollment requires at least Tk '.number_format($minimumFee, 2).' in Bangladesh Taka.');
        }

        $user->update([
            'access_type_id' => $proAccessType->id,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Pro enrollment successful. You are now upgraded to '.$proAccessType->name.'.');
    }

    // Dashboard
    public function dashboard()
    {
        return view('user.dashboard');
    }

    // Logout
    public function logout()
    {
        session()->forget([
            'user_id',
            'user_name',
            'user_logged_in',
        ]);

        return redirect()->route('user.login')
            ->with('success', 'Logout Successfully');
    }

    // Profile
    public function profile()
    {
        $user = User::findOrFail(session('user_id'));

        return view('user.profile', compact('user'));
    }

    public function profile_update(Request $request)
    {
        $user = User::findOrFail(session('user_id'));

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'number' => ['required', 'digits:10', 'unique:users,number,'.$user->id],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'in:male,female,other,prefer-not-to-say'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'bio' => $request->bio,
        ];

        if (
            $user->name == $payload['name'] &&
            $user->email == $payload['email'] &&
            $user->number == $payload['number'] &&
            ($user->address ?? '') == ($payload['address'] ?? '') &&
            ($user->city ?? '') == ($payload['city'] ?? '') &&
            ($user->state ?? '') == ($payload['state'] ?? '') &&
            ($user->country ?? '') == ($payload['country'] ?? '') &&
            ($user->gender ?? '') == ($payload['gender'] ?? '') &&
            ($user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') == ($payload['date_of_birth'] ?? '') &&
            ($user->bio ?? '') == ($payload['bio'] ?? '')
        ) {
            return back()->with('error', 'No changes found.');
        }

        $user->update($payload);
        session(['user_name' => $request->name]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function change_password_update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail(session('user_id'));

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'New password cannot be the same as the current password.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully');
    }
}
