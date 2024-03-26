<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
            'title' => 'required',
            'body' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'sheesh',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'title is required',
            'body.required' => 'message is required',
        ];
    }
}
