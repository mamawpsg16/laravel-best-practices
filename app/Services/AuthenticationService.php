<?php
namespace App\Services;

use App\Models\User;
use App\Events\Example;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Mail\UserRegisteredMailExample;

class AuthenticationService{
    public function registerUser($data){
        return DB::transaction(function () use($data) {
    
            $data['password'] = bcrypt($data['password']);
    
            $user = User::create($data);

            session()->regenerate();
            
            Auth::login($user);
            
            // Mail::to($user->email)->queue(new UserRegisteredMailExample($user));

            event(new Registered($user));

            return response(['status' => 'success']);
        });
    }
    public function loginUser($data){
        return DB::transaction(function () use($data) {
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remembered'])){

                session()->regenerate();

                $user = Auth::user();

                event(new Example());

                return response()->json(['user' => $user, 'isLoggedIn' => true]);
            }
            return response()->json(['error' => 'Invalid credentials', 'isLoggedIn' => false],422);
        });
    }
}