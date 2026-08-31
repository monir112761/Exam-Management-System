<?php

use App\Http\Controllers\AccessTypeController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/api/test', fn () => response()->json(['status' => 'ok']));

    Route::middleware(['admin.auth', 'permission:exam.view'])->group(function () {
        Route::get('/api/exams', [ExamController::class, 'apiIndex']);
        Route::get('/api/exams/{id}', [ExamController::class, 'apiShow']);
        Route::post('/api/exams', [ExamController::class, 'apiStore'])->middleware('permission:exam.create');
        Route::put('/api/exams/{id}', [ExamController::class, 'apiUpdate'])->middleware('permission:exam.edit');
        Route::delete('/api/exams/{id}', [ExamController::class, 'apiDestroy'])->middleware('permission:exam.delete');
        Route::post('/api/exams/{id}/schedule', [ExamController::class, 'apiSchedule'])->middleware('permission:exam.schedule');
        Route::post('/api/exams/{id}/publish', [ExamController::class, 'apiPublish'])->middleware('permission:exam.publish');

        Route::get('/api/questions', [QuestionController::class, 'apiIndex'])->middleware('permission:question.view');
        Route::post('/api/questions', [QuestionController::class, 'apiStore'])->middleware('permission:question.create');
        Route::put('/api/questions/{id}', [QuestionController::class, 'apiUpdate'])->middleware('permission:question.edit');
        Route::delete('/api/questions/{id}', [QuestionController::class, 'apiDestroy'])->middleware('permission:question.delete');
        Route::post('/api/exams/{id}/questions', [ExamController::class, 'apiAttachQuestion'])->middleware('permission:question.assign');
        Route::put('/api/exams/{id}/questions/{questionId}', [ExamController::class, 'apiUpdateQuestionAssignment'])->middleware('permission:question.assign');
        Route::delete('/api/exams/{id}/questions/{questionId}', [ExamController::class, 'apiDeleteQuestionAssignment'])->middleware('permission:question.delete');

        Route::get('/api/access-types', [AccessTypeController::class, 'apiIndex'])->middleware('permission:settings.view');
        Route::post('/api/access-types', [AccessTypeController::class, 'apiStore'])->middleware('permission:settings.manage');
        Route::put('/api/access-types/{id}', [AccessTypeController::class, 'apiUpdate'])->middleware('permission:settings.manage');
        Route::delete('/api/access-types/{id}', [AccessTypeController::class, 'apiDestroy'])->middleware('permission:settings.manage');

        Route::get('/api/roles', [RoleController::class, 'apiIndex'])->middleware('permission:role.view');
        Route::post('/api/roles', [RoleController::class, 'apiStore'])->middleware('permission:role.create');
        Route::put('/api/roles/{id}', [RoleController::class, 'apiUpdate'])->middleware('permission:role.edit');
        Route::delete('/api/roles/{id}', [RoleController::class, 'apiDestroy'])->middleware('permission:role.delete');

        Route::get('/api/permissions', [PermissionController::class, 'apiIndex'])->middleware('permission:role.view');
        Route::post('/api/permissions', [PermissionController::class, 'apiStore'])->middleware('permission:role.assign_permission');
        Route::put('/api/permissions/{id}', [PermissionController::class, 'apiUpdate'])->middleware('permission:role.assign_permission');
        Route::delete('/api/permissions/{id}', [PermissionController::class, 'apiDestroy'])->middleware('permission:role.assign_permission');

        Route::get('/api/results', [ResultController::class, 'apiIndex'])->middleware('permission:result.view');
        Route::get('/api/results/{id}', [ResultController::class, 'apiShow'])->middleware('permission:result.view');
    });
});
