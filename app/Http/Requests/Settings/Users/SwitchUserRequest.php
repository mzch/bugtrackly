<?php

namespace App\Http\Requests\Settings\Users;

use Illuminate\Foundation\Http\FormRequest;

class SwitchUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role_id === 1;
    }
}
