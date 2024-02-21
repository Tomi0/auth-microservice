<?php

namespace App\UI\Http\Validators\Authentication\User;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CreateUserValidator extends FormRequest
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
            'full_name' => ['required', 'max:255'],
            'email' => ['required', 'email'],
            'password' => 'required|min:6',
        ];
    }
}
