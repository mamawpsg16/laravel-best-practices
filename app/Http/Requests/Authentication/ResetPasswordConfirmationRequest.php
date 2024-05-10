<?php

namespace App\Http\Requests\Authentication;

use App\Rules\CheckPasswordIfUnique;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordConfirmationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ["required","string","min:8","confirmed",  new CheckPasswordIfUnique],
            'password_confirmation' => ["required_with:password"],
            'token' => ["required"],

        ];
    }
}
