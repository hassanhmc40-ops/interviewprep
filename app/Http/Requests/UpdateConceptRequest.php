<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConceptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'explanation' => ['required', 'string'],
            'difficulty_level' => ['required', 'in:junior,mid,senior'],
            'status' => ['required', 'in:to_review,in_progress,mastered'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Concept title is required.',
            'title.max' => 'Concept title cannot exceed 255 characters.',
            'explanation.required' => 'Explanation is required.',
            'difficulty_level.required' => 'Please select a difficulty level.',
            'difficulty_level.in' => 'Please select a valid difficulty level.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Please select a valid status.',
        ];
    }
}