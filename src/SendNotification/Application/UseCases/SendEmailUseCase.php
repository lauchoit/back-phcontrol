<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Application\UseCases;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Factory\EmailMessageBuilder;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Repository\SendNotificationRepository;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotification;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;

readonly class SendEmailUseCase
{
    public function __construct(
        private SendNotificationRepository $sendNotificationRepository,
        private CreateSendNotificationUseCase $createSendNotificationUseCase,
    ) {}

    public function execute(TemplateNotification $templateNotification, User $user, $variables = []): bool
    {
        $message = EmailMessageBuilder::create()
            ->to($user->getEmail())
            ->subject($templateNotification->getSubject())
            ->html($templateNotification->getBodyHtml())
            ->withRequiredVariables($templateNotification->getVariables())
            ->with($user)
            ->with([...$variables, 'appName' => config('app.name'), 'supportEmail' => config('app.mail_support_address')])
            ->build();
        //                'appName' => config('app.name'),
        //                'activationLink' => 'https://example.com/activate?token=some_token',
        //                'supportEmail' => config('mail.support_email'),
        $this->createSendNotificationUseCase->execute($message);

        return $this->sendNotificationRepository->sendNotification($message);
    }
}
