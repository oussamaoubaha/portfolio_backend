<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'location' => 'nullable|string|max:255',
            'about_text' => 'nullable|string',
            'hero_image' => 'nullable', // Allow string URL or File upload
            'cv_url' => 'nullable|string',
            'social_links' => 'nullable|array',
            'description' => 'nullable|string',
        ];
    }
}
