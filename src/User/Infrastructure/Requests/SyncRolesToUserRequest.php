<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;

class SyncRolesToUserRequest extends FormRequest
{
    use ValidationResponse;

    public function rules(): array
    {
        return [
            'userId' => 'required|uuid|exists:users,id',
            'roleIds' => 'array',
            'roleIds.*' => 'uuid|exists:roles,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
