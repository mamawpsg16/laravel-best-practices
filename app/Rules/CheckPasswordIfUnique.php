<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPasswordIfUnique implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = request()->input('email');
        $old_password = User::where('email', $email)->value('password');
        // Check if the new password is different from the current password
        if(Hash::check($value, $old_password)){
            $fail('Old password cannot be reused. Please select a new one');
        }
    }
}
