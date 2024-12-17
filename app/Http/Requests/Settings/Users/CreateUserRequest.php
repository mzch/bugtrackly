<?php

namespace App\Http\Requests\Settings\Users;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'email' => ['required', 'string','lowercase', 'email', 'max:255', 'unique:users'],
            'role_id' => ['required', 'int'],
            'password' => ['required', 'string', 'min:8'],
            'send_password' => ['boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
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

}
