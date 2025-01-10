<?php

namespace App\Http\Requests\BugComment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBugCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'files' => 'nullable|array',
            'files.*' => 'file|max:2048',
        ];
    }
}
