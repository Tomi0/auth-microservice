<?php

namespace App\UI\Http\Validators\Authentication\User;

use Illuminate\Foundation\Http\FormRequest;

class SearchUserValidator extends FormRequest
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
            'email' => 'nullable|string',
            'admin' => 'nullable|boolean',
        ];
    }
}
