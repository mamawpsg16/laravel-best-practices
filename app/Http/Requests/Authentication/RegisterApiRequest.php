<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class RegisterApiRequest extends FormRequest
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
            'name' => ["required","string","max:255","unique:users,name"],
            'email' => ["required","email","unique:users,email"],
            'password' => ["required","string","min:8","confirmed"],
            'password_confirmation' => ["required_with:password"],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The username field is required.',
            'name.string' => 'The username must be a string.',
            'name.max' => 'The username must not be greater than :max characters.',
            'name.unique' => 'The username has already been taken.',
        ];
    }
}
