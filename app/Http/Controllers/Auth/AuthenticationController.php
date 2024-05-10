<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\ResetPassword;
use App\Events\EmailVerification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Two\InvalidStateException;
use App\Http\Requests\Authentication\LoginApiRequest;
use App\Http\Requests\Authentication\RegisterApiRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Http\Requests\Authentication\ChangePasswordRequest;
use App\Http\Requests\Authentication\ResetPasswordConfirmationRequest;

class AuthenticationController extends Controller
{

    public function __construct(protected AuthenticationService $authenticationService){

    }
  
    public function user(Request $request){
        $user = [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'isVerified' => auth()->user()->email_verified_at ?? false,
            'provider' => auth()->user()->provider
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

    public function verify(EmailVerificationRequest $request, $id, $hash){
        $request->fulfill();
        return redirect('/');
    }

    public function resendVerification(Request $request){
        event(new Registered($request->user()));
        return response(['message' => 'Verification link sent']);
    }

    public function changePassword(ChangePasswordRequest $request){
        $validated = $request->validated();

        $request->user()->fill([
            'password' => Hash::make($validated['new_password'])
        ])->save();

        return response(['message' => 'Password successfully updated']);
    }

    public function resetPassword(ResetPasswordRequest $request){
        
 
        $email = $request->validated();
        event(new ResetPassword($email));
   
        return response(['message' => 'Password reset link sent, kindly check your email.']);
    }

    public function resetPasswordConfirmation(ResetPasswordConfirmationRequest $request){
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return response(['message' => 'Password succesfully reset, kindly login your new credentials.']);
    }
}
