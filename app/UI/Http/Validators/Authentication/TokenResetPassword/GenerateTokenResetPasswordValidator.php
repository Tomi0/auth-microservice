<?php

namespace App\UI\Http\Validators\Authentication\TokenResetPassword;

use Illuminate\Foundation\Http\FormRequest;

class GenerateTokenResetPasswordValidator extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
