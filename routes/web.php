<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Spa\ViewController;
use App\Http\Controllers\Auth\AuthenticationController;

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

Route::get('/auth/google/redirect', [AuthenticationController::class, 'socialAuthenticationRedirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback',[AuthenticationController::class, 'socialAuthenticationCallback'])->name('auth.google.callback');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/register',[AuthenticationController::class,'register']);

Route::prefix('email')->group(function () {
    Route::get('/verify/{id}/{hash}',[AuthenticationController::class,'verify'])->middleware(['auth:sanctum','signed'])->name('verification.verify');
});
Route::post('/forgot-password',[AuthenticationController::class,'resetPassword'])->name('password.request');
Route::post('/reset-password/confirmation',[AuthenticationController::class,'resetPasswordConfirmation'])->name('password.update');

/** VUE SPA */
Route::get('/{any?}', ViewController::class)->where('any', '.*');

Route::get('/reset-password/{token}', function (string $token) {
    // return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
// Route::middleware(['auth'])->group(function () {
//     Route::post('/logout', [AuthenticationController::class,'logout'])->name('logout');
//     Route::get('examples',function(){
//         return view('example');
//     });
// });


 
