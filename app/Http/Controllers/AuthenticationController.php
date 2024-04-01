<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Two\InvalidStateException;

class AuthenticationController extends Controller
{
  
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function registerStore(Request $request)
    {
        return DB::transaction(function () use($request) {

            $validators = Validator::make($request->all(), [
                'name' => 'required',
                'email' => ['required', 'email', 'unique:users'],
                'password' => 'confirmed',
                'password_confirmation' => 'required',
            ]);
            $data = $validators->validated();
            $data['password'] = bcrypt($data['password']);
            if($validators->fails()){
            }

            $user = User::create($data);
            Auth::login($user);
            return response(['status' => 'success']);
        });
    }

    public function login()
    {
        return view('auth.login');
    }
    public function loginStore(Request $request)
    {
        return DB::transaction(function () use($request) {

            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
     
                return response(['success' => true]);
            }

        });
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect()->route('posts.index');

    }

    public function socialAuthenticationRedirect(){
        return Socialite::driver('google')->redirect();
    }
    public function socialAuthenticationCallback(){
        try {
            $user = Socialite::driver('google')->user();
    
            $authUser = User::updateOrCreate(
                ['provider_id' => $user->getId()],
                [
                    'provider' => 'google',
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                ]
            );
    
            Auth::login($authUser);
    
            return redirect()->route('dashboard');
        } catch (InvalidStateException $exception) {
            // Authentication denied
            return redirect()->route('login.create');
        }
    }
}
