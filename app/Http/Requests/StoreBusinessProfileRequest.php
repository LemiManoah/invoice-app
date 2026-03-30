<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\BusinessProfile;
use Illuminate\Foundation\Http\FormRequest;

final class StoreBusinessProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', BusinessProfile::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'contacts' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'po_box' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'signature_upload' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'signature_data' => ['nullable', 'string', 'regex:/^data:image\/png;base64,/'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_signature' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Enter the business name.',
            'email.email' => 'Enter a valid business email address.',
            'logo.mimes' => 'The logo must be a JPEG, JPG, or PNG file.',
            'signature_upload.mimes' => 'The uploaded signature must be a JPEG, JPG, or PNG file.',
            'signature_data.regex' => 'The drawn signature format is invalid.',
        ];
    }
}
