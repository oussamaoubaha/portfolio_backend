<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'period' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'missions' => 'nullable|array',
            'missions.*' => 'string|max:1000',
        ];
    }
}
