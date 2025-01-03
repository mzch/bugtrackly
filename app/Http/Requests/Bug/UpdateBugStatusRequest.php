<?php

namespace App\Http\Requests\Bug;

use App\Models\Bug;
use App\Rules\ValidBugStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBugStatusRequest extends DefaultBugRequest
{
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
