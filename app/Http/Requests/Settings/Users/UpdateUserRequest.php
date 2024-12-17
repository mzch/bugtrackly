<?php

namespace App\Http\Requests\Settings\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'role_id' => ['required', 'int'],
            'password' => ['nullable', 'string', 'min:8'],
            'send_new_password' => ['boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            'delete_old_photo' => ['required', 'boolean'],
        ];
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'first_name' => 'prénom',
            'last_name' => 'nom',
            'email' => 'email',
            'role_id' => 'role',
            'password' => 'mot de passe',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'delete_old_photo' => filter_var($this->delete_old_photo, FILTER_VALIDATE_BOOLEAN),
            'send_new_password' => filter_var($this->send_new_password, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
