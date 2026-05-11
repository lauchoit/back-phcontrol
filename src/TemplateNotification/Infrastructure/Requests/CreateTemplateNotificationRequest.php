<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Lauchoit\LaravelHexMod\Shared\Responses\ValidationResponse;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Mappers\NotificationChannel;

class CreateTemplateNotificationRequest extends FormRequest
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
        $locale = $this->input('locale');
        $channel = $this->input('notificationChannel');

        return [
            'key' => [
                'required', 'string', 'max:255',
                Rule::unique('template_notifications', 'key')
                    ->where(fn ($q) => $q
                        ->where('locale', $locale)
                        ->where('notification_channel', $channel)
                    ),
            ],
            'locale' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'bodyHtml' => 'required|string',
            'version' => 'required|integer',
            'isActive' => 'required|boolean',
            'variables' => 'required|json',
            'notificationChannel' => ['required', new Enum(NotificationChannel::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'key.unique' => 'combination.key.locale.already.exists',
        ];
    }
}
