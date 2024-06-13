<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
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
            'title' => ["required", "max:100"],
            'email' => ["sometimes", "email", "exists:users,email"],
            'description' => ["required","max:1000"],
            'report_type' => ["required"],
            'attachments' => ["sometimes"],
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'Email address not found, Please double-check and try again.',
        ];
    }
}
