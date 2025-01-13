<?php

namespace App\Http\Requests\Bug;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBugRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'file|max:2048',
        ];
    }
}
