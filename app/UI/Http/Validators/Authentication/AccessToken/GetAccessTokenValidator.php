<?php

namespace App\UI\Http\Validators\Authentication\AccessToken;

use Illuminate\Foundation\Http\FormRequest;

class GetAccessTokenValidator extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clientSecret' => 'required|string',
            'code' => 'required|string',
            'clientName' => 'required|string',
        ];
    }
}
