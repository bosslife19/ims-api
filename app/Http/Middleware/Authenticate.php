<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if($request->is('api/*')) {
            $ErrorResponse = [
                'success' => false,
                'message' =>'Invalid Token',
            ];
            abort(response()->json($ErrorResponse, 403));
        }

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
