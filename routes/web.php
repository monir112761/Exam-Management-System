<?php

use App\Http\Controllers\AccessTypeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Admin
Route::get('/admin', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login_store'])->name('admin.login.store');

Route::middleware(['admin.auth'])->group(function () {
    Route::get('/cms', [AdminController::class, 'dashboard'])->name('cms.dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'profile_update'])->name('admin.profile.update');
    Route::post('/admin/profile/change-password', [AdminController::class, 'change_password_update'])->name('admin.password.update');
    Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // User
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/delete/{id}', [AdminController::class, 'user_delete'])->name('admin.users.delete');

    // Question
    Route::get('/admin/questions', [QuestionController::class, 'index'])->name('admin.questions');
    Route::get('/admin/questions/export', [QuestionController::class, 'exportTemplate'])->name('admin.questions.export');
    Route::post('/admin/questions/import', [QuestionController::class, 'import'])->name('admin.questions.import');
    Route::get('/admin/questions/add', [QuestionController::class, 'create'])->name('admin.questions.add');
    Route::post('/admin/questions/store', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/admin/questions/edit/{id}', [QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::post('/admin/questions/update/{id}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::get('/admin/questions/delete/{id}', [QuestionController::class, 'delete'])->name('admin.questions.delete');

    // Exam
    Route::get('/admin/exams', [ExamController::class, 'index'])->name('admin.exams');
    Route::get('/admin/exams/add', [ExamController::class, 'create'])->name('admin.exams.add');
    Route::post('/admin/exams/store', [ExamController::class, 'store'])->name('admin.exams.store');
    Route::get('/admin/exams/edit/{id}', [ExamController::class, 'edit'])->name('admin.exams.edit');
    Route::post('/admin/exams/update/{id}', [ExamController::class, 'update'])->name('admin.exams.update');
    Route::get('/admin/exams/delete/{id}', [ExamController::class, 'delete'])->name('admin.exams.delete');

    // Result
    Route::get('/admin/results', [ResultController::class, 'index'])->name('admin.results');
    Route::get('/admin/results/view/{id}', [ResultController::class, 'view'])->name('admin.results.view');

    // Access Types
    Route::get('/admin/access-types', [AccessTypeController::class, 'index'])->name('admin.access-types');
    Route::post('/admin/access-types/store', [AccessTypeController::class, 'store'])->name('admin.access-types.store');
    Route::post('/admin/access-types/update/{accessType}', [AccessTypeController::class, 'update'])->name('admin.access-types.update');
    Route::get('/admin/access-types/delete/{accessType}', [AccessTypeController::class, 'destroy'])->name('admin.access-types.delete');

    // Roles
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles');
    Route::post('/admin/roles/store', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::post('/admin/roles/update/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::get('/admin/roles/delete/{role}', [RoleController::class, 'destroy'])->name('admin.roles.delete');

    // Permissions
    Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions');
    Route::post('/admin/permissions/store', [PermissionController::class, 'store'])->name('admin.permissions.store');
    Route::post('/admin/permissions/update/{permission}', [PermissionController::class, 'update'])->name('admin.permissions.update');
    Route::get('/admin/permissions/delete/{permission}', [PermissionController::class, 'destroy'])->name('admin.permissions.delete');

});

// Public / CMS landing page
Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'login_store'])->name('user.login.store');
Route::get('/register', [UserController::class, 'register'])->name('user.register');
Route::post('/register', [UserController::class, 'register_store'])->name('user.register.store');
Route::get('/verify-email/{token}', [UserController::class, 'verifyEmail'])->name('user.verify-email');

Route::middleware(['user.auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::get('/pro/enroll', [UserController::class, 'proEnroll'])->name('user.pro.enroll');
    Route::post('/pro/enroll', [UserController::class, 'proEnrollStore'])->name('user.pro.enroll.store');

    // Exam
    Route::get('/available-exams', [ExamController::class, 'availableExam'])->name('available.exams');
    Route::get('/exam/start/{id}', [ExamController::class, 'start'])->name('exam.start');
    Route::post('/exam/submit/{id}', [ExamController::class, 'submit'])->name('exam.submit');

    // Result
    Route::get('/my-results', [ResultController::class, 'userResults'])->name('user.results');
    Route::get('/my-results/view/{id}', [ResultController::class, 'userResultView'])->name('user.results.view');

    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [UserController::class, 'profile_update'])->name('user.profile.update');
    Route::post('/profile/change-password', [UserController::class, 'change_password_update'])->name('user.password.update');
});

Route::get('/user/login', [UserController::class, 'login'])->name('user.login');
