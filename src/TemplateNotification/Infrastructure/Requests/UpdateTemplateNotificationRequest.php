<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers\NotificationChannel;

class UpdateTemplateNotificationRequest extends FormRequest
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
            'key' => 'string|max:255',
            'locale' => 'string|max:255',
            'subject' => 'string|max:255',
            'bodyHtml' => 'string|max:255',
            'version' => 'integer',
            'isActive' => 'boolean',
            'variables' => 'json',
            'notificationChannel' => [new Enum(NotificationChannel::class)],
        ];
    }
}
