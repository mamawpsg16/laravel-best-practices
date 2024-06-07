<?php

namespace App\Http\Middleware\Authentication;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BannedUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    { 
        $user = Auth::user();
        if (!empty($user) && $user->isBanned()) {
            Auth::guard('web')->logout();
        
            $request->session()->invalidate();
            
            $request->session()->regenerateToken();
            // session()->flash('banned', [
            //     'error' => 'Your account has been banned.',
            //     'status_code' => 403,
            // ]);
            return redirect('login')->with(['banned' => [
                'error' => 'Your account has been banned.',
                'status_code' => 403,
            ]]);
        }

        return $next($request);
    }
}
