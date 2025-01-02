<?php

namespace App\Http\Requests\Bug;

use Illuminate\Foundation\Http\FormRequest;

class ShowBugRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $project = $this->route('project');
        return $user->can('manage-projects') || ( $user->can('report-bug') && $project->users->pluck('id')->contains($user->id) );
    }
}
