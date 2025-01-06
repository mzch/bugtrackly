<?php

namespace App\Http\Requests\BugComment;

use App\Models\Bug;
use App\Rules\ValidBugStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreBugCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Bug $bug */
        $bug = $this->route('bug');

        return [
            'content' => ['nullable', 'string'],
            'priority' => ['required','integer','between:1,5'],
            'status' => ['required', 'integer', new ValidBugStatus($bug)],
            'assigned_user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
