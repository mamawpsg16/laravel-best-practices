<?php
namespace App\Services;

use App\Events\Example;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthenticationService{
    public function registerUser($data){
        return DB::transaction(function () use($data) {
    
            $data['password'] = bcrypt($data['password']);
    
            $user = User::create($data);
    
            session()->regenerate();
            
            Auth::login($user);
            
            event(new Registered($user));

            return response(['status' => 'success']);
        });
    }
    public function loginUser($data){
        return DB::transaction(function () use($data) {
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remembered'])){
                session()->regenerate();

                $user = Auth::user();
                $event = event(new Example());
                return response()->json(['user' => $user, 'isLoggedIn' => true]);
            }
            return response()->json(['error' => 'Invalid credentials', 'isLoggedIn' => false],422);
        });
    }
}