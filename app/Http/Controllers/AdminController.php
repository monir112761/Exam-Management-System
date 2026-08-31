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
use App\Models\Exam;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function login()
    {
        return redirect()->route('login', ['role' => 'admin']);
    }

    public function login_store(Request $request)
    {
        $request->merge(['role' => 'admin']);

        return app(UserController::class)->login_store($request);
    }

    public function dashboard()
    {

        $totalUsers = User::count();
        $totalExams = Exam::count();
        $totalQuestions = Question::count();
        $totalResults = Result::count();
        $totalAccessTypes = AccessType::count();
        $publishedExams = Exam::whereIn('status', ['published', 'ongoing'])->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalExams',
            'totalQuestions',
            'totalResults',
            'totalAccessTypes',
            'publishedExams'
        ));
    }

    public function profile()
    {
        $admin = Admin::findOrFail(session('admin_id'));

        return view('admin.profile', compact('admin'));
    }

    public function profile_update(Request $request)
    {
        $admin = Admin::findOrFail(session('admin_id'));

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admin,email,'.$admin->id],
            'number' => ['nullable', 'string', 'max:20', 'unique:admin,number,'.$admin->id],
        ]);

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
        ];

        $admin->update($payload);
        session(['admin_name' => $request->name]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function change_password_update(Request $request)
    {
        $admin = Admin::findOrFail(session('admin_id'));

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if (! Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        if (Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'New password cannot be the same as the current password.');
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function logout()
    {
        Session::forget(['admin_id', 'admin_name', 'admin_logged_in']);

        return redirect()->route('admin.login')->with('success', 'Logout successfully');
    }

    public function users(Request $request)
    {
        $totalUsers = User::count();
        $query = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('number', 'like', '%'.$search.'%');
            });
        }
        $users = $query->latest()->paginate(10);
        $filterUsers = $request->filled('search')
            ? $users->total()
            : 0;

        return view('admin.user', compact(
            'users',
            'totalUsers',
            'filterUsers'
        ));
    }

    public function user_delete($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }
}
