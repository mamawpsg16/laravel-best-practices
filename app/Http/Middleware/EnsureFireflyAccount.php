<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFireflyAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $email_explode = explode("@", $request->email);
        // if ($email_explode[1]) {
        //     return response(['error' => 'Invalid Domain']);
        // }
        return $next($request);
    }
}
