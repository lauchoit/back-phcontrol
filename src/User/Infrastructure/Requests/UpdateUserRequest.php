<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;

class UpdateUserRequest extends FormRequest
{
    use ValidationResponse;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userIdToIgnore = $this->route('userId') ?? $this->user()?->id;

        return [
            'name' => 'string|max:255',
            'lastname' => 'string|max:255',
            'email' => [
                'string',
                'max:255',
                'email',
                Rule::unique('users', 'email')->ignore($userIdToIgnore, 'id'),
            ],
            'password' => 'string|max:255',
            'phone' => 'string|max:255',
            'isActive' => 'boolean',
            'language' => 'string|in:es,en',
        ];
    }
}
