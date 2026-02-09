<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => 'required|string|max:255',
            'role' => 'nullable|string|max:100',
            'content' => 'required|string|max:2000',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_active' => 'nullable|boolean',
        ];
    }
}
