<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:2|unique:products',
            'isActive' => 'required|boolean',
            'order' => 'required|integer',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'validation.required',
            'name.string' => 'validation.string',
            'name.max' => 'validation.max.string(:max)',
            'name.min' => 'validation.min.string(:min)',
            'name.unique' => 'name.validation.unique',
            'isActive.required' => 'validation.required',
            'isActive.boolean' => 'validation.boolean',
            'order.required' => 'validation.required',
            'order.integer' => 'validation.integer',
        ];
    }
}
