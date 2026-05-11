<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;

class ResetPasswordRequest extends FormRequest
{
    use ValidationResponse;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|max:255|confirmed',
        ];
    }
}
