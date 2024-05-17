<?php

namespace App\Http\Controllers\Spa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ViewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        if(Auth::check() && ($pathInfo == '/register' || $pathInfo == '/login' || Str::startsWith($pathInfo, '/reset-password'))){
            return redirect('/');
        }
        $user = session('auth-user');
        return view('app',['user' => json_encode($user)]);
    }
}
