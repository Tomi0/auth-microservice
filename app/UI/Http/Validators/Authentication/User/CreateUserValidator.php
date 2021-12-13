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

    #[ArrayShape(['username' => "string", 'email' => "string", 'password' => "string"])]
    public function rules(): array
    {
        return [
            'username' => 'required|unique:user|min:3|max:50|string',
            'email' => 'required|unique:user|email',
            'password' => 'required|min:6',
        ];
    }
}
