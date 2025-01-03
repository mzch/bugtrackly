<?php

namespace App\Http\Requests\Bug;

use Illuminate\Foundation\Http\FormRequest;

class DefaultBugRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->checkUserCapabilities();
    }

    /**
     * Vérifie que l'utilisateur est un admin ou bien a accès au projet
     * @return bool
     */
    protected function checkUserCapabilities(): bool
    {
        $user = $this->user();
        $project = $this->route('project');
        return $user->can('manage-projects') || ( $user->can('report-bug') && $project->users->pluck('id')->contains($user->id) );
    }

}
