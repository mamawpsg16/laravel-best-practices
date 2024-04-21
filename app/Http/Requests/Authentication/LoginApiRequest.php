<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class LoginApiRequest extends FormRequest
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
        $validations = [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remembered' => ['required'],
        ];
        
        $urlPath  = $this->getPathInfo();

        if($urlPath != '/auth/google/callback'){
            return $validations;
        }

        return [];
    }
}
