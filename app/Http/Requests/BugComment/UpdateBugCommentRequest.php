<?php

namespace App\Http\Requests\BugComment;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateBugCommentRequest extends DefaultBugCommentRequest
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
        ];
    }
}
