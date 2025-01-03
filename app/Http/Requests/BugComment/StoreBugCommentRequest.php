<?php

namespace App\Http\Requests\BugComment;

use Illuminate\Foundation\Http\FormRequest;

class StoreBugCommentRequest extends DefaultBugCommentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkUserCapabilities()
            && $this->checkBugRelativeToProject();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
        ];
    }
}
