<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthenticationService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Two\InvalidStateException;
use App\Http\Requests\Authentication\LoginApiRequest;
use App\Http\Requests\Authentication\RegisterApiRequest;

class AuthenticationController extends Controller
{

    public function __construct(protected AuthenticationService $authenticationService){

    }
  
    public function user(Request $request){
        $user = [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ];
        
        return response(['user' => $user]);
    }

    public function register(RegisterApiRequest $request)
    {
        $data = $request->validated();
        return $this->authenticationService->registerUser($data);
    }

    public function login(LoginApiRequest $request)
    {
        $credentials = $request->validated();
        return $this->authenticationService->loginUser($credentials);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();
    
        return response(['success' => true]);

    }

    public function socialAuthenticationRedirect(){
        return Socialite::driver('google')->redirect();
    }

    public function socialAuthenticationCallback(LoginApiRequest $request){
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
            
            Auth::loginUsingId($authUser->id);

          
            session()->flash('auth-user', $authUser->toArray());

            return redirect('/');

        } catch (InvalidStateException $exception) {
            // Authentication denied
            return redirect('/login');
        }
    }
}
