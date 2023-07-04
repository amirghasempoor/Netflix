<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($this->user()->id)],
            'email' => 'email',
            'avatar' => 'mimes:png,jpg',
            'role' => 'required|exists:roles,id',
        ];
    }
}
