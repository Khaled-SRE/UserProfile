<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserProfileImageRequest extends FormRequest
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
            'profile_image' => ['nullable', 'image', 'max:2048'], // ✅ image file
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->route('userId'),
        ]);
    }
}
