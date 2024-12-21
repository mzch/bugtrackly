<?php

namespace App\Http\Requests\Settings\Users;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage-users') &&  $this->user->id !== $this->user()->id;
    }
}
