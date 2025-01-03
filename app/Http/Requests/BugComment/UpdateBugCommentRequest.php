<?php

namespace App\Http\Requests\BugComment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBugCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->checkUserCapabilities()
                && $this->checkBugRelativeToProject()
                && $this->checkResponseRelativeToBug();
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

    /**
     * Vérifie que l'utilisateur est un admin ou bien a accès au projet
     * @return bool
     */
    private function checkUserCapabilities():bool
    {
        $user = $this->user();
        $project = $this->route('project');
        return $user->can('manage-projects') || ( $user->can('report-bug') && $project->users->pluck('id')->contains($user->id) );
    }

    /**
     * Vérifie que le bug en question est relatif au projet
     * @return bool
     */
    private function checkBugRelativeToProject():bool{
        $project = $this->route('project');
        $bug = $this->route('bug');
        return $project->id === $bug->project_id;
    }

    /**
     * Vérifie que la réponse du bug en question est relatif au bug
     * @return bool
     */
    private function checkResponseRelativeToBug():bool{
        $bug = $this->route('bug');
        $bugComment = $this->route('bugComment');
        return $bug->id === $bugComment->bug_id;

    }

}
