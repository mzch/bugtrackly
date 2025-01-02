<?php

namespace App\Http\Requests\Bug;

use App\Models\Bug;
use App\Rules\ValidBugStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBugStatusRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $bug = $this->route('bug');
        // Assurez-vous que l'instance est bien un objet Bug
        if (!$bug instanceof Bug) {
            abort(404, "Bug non trouvé");
        }
        return [
            'status' => ['required', 'integer', new ValidBugStatus($bug)],
        ];
    }
}
