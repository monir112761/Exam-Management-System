<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $userId = session('user_id');
        $user = $userId ? User::find($userId) : null;

        if (! $user || ! $user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Missing permission: '.$permission], 403);
            }

            abort(403, 'Forbidden. Missing permission: '.$permission);
        }

        return $next($request);
    }
}
