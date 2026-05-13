<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Domain name is required.',
            'name.max' => 'Domain name cannot exceed 255 characters.',
            'color.required' => 'Please select a color.',
            'color.regex' => 'Please provide a valid hex color code.',
        ];
    }
}