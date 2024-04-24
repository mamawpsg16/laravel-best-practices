<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register',[AuthenticationController::class,'register']);


Route::get('/auth/google/redirect', [AuthenticationController::class, 'socialAuthenticationRedirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback',[AuthenticationController::class, 'socialAuthenticationCallback'])->name('auth.google.callback');

Route::middleware(['guest'])->group(function(){

});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthenticationController::class,'logout'])->name('logout');
    Route::get('examples',function(){
        return view('example');
    });
});


Route::get('/{any?}', function () {
    $user = session('auth-user');
    return view('app',['user' => json_encode($user)]);
})->where('any', '.*');
 
