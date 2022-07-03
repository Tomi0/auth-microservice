<?php

namespace App\UI\Http\Validators\Authentication\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPasswordValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'token' => 'required|string',
        ];
    }
}
