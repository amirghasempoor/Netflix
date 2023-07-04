<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users,username',
            'password' => 'required|confirmed|string',
            'email' => 'email|unique:users,email',
            'avatar' => 'mimes:png,jpg',
            'role_id' => 'required|exists:roles,id',
        ];
    }
}
