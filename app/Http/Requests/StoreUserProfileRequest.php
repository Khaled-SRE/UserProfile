<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserProfileRequest extends FormRequest
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
    public function rules()
    {

        return [
            'user_id' => ['required', Rule::unique('user_profile', 'user_id')],
            'email' => ['required', 'email', Rule::unique('user_profile', 'email')],
            'first_name' => ['required', 'string'],
            'last_name' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'phone' => ['nullable', 'string'],
            'locale' => ['required', Rule::in(['ar', 'en'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->route('userId'),
        ]);
    }
}
