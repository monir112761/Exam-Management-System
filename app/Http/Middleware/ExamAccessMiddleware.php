<?php

namespace App\Http\Middleware;

use App\Models\Exam;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $examId = $request->route('id') ?? $request->route('exam');
        $exam = $examId ? Exam::find($examId) : null;

        $userId = session('user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $exam || ! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized exam access.'], 403);
            }

            abort(403, 'Unauthorized exam access.');
        }

        if (! $user->canAccessExam($exam)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your access type cannot access this exam.'], 403);
            }

            abort(403, 'Your access type cannot access this exam.');
        }

        return $next($request);
    }
}
