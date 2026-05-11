<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;

class UpdateProductRequest extends FormRequest
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
        return [
            'name' => 'string|max:255',
            'isActive' => 'boolean',
            'order' => 'integer',
        ];
    }
}
