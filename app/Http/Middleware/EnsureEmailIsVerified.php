<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the authenticated user's email is verified
        if ($request->user() && !$request->user()->is_email_verified) {
            return response()->json([
                'status' => false,
                'msg' => 'Your email is not verified',
                'data' =>[],
            ], 403);

        }

        return $next($request);
    }
}
