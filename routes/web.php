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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/redirect', [AuthenticationController::class, 'socialAuthenticationRedirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback',[AuthenticationController::class, 'socialAuthenticationCallback'])->name('auth.google.callback');
Route::get('/register',[AuthenticationController::class,'register'])->name('register.create');
Route::post('/register',[AuthenticationController::class,'registerStore'])->name('register.store');
Route::get('/login',[AuthenticationController::class,'login'])->name('login.create');
Route::post('/login',[AuthenticationController::class,'loginStore'])->name('login.store');
Route::middleware(['auth'])->group(function () {
    Route::resource('posts',PostController::class);
    Route::post('/logout', [AuthenticationController::class,'logout'])->name('logout');
    
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    Route::get('examples',function(){
        return view('example');
    });
});


Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
 
