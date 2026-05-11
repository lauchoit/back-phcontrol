<?php

namespace Lauchoit\LaravelHexMod\Auth\Application\UseCases;

use Lauchoit\LaravelHexMod\Auth\Domain\Repository\AuthRepository;
use Lauchoit\LaravelHexMod\SendNotification\Application\UseCases\SendEmailUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\FindByKeyTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\User\Application\UseCases\FindByEmailPhoneUserUseCase;

readonly class ForgetPasswordUserCase
{
    public function __construct(
        private AuthRepository $authRepository,
        private FindByEmailPhoneUserUseCase $findByEmailPhoneUserUseCase,
        private FindByKeyTemplateNotificationUseCase $findByKeyTemplateNotificationUseCase,
        private SendEmailUseCase $sendEmailUseCase,
    ) {}

    public function execute($data): string
    {
        $user = $this->findByEmailPhoneUserUseCase->execute($data);
        $response = $this->authRepository->forgetPassword($user->getEmail());
        $template = $this->findByKeyTemplateNotificationUseCase->execute('forget-password', $user->getLanguage());
        $variablesTemplate = [
            'link' => $response,
        ];
        $this->sendEmailUseCase->execute($template, $user, $variablesTemplate);

        return $response;
    }
}
