<?php

namespace Lauchoit\LaravelHexMod\User\Application\UseCases;

use Illuminate\Support\Facades\Log;
use Lauchoit\LaravelHexMod\SendNotification\Application\UseCases\SendEmailUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\FindByKeyTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\User\Domain\Entity\User;
use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;
use Throwable;

readonly class CreateUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private FindByKeyTemplateNotificationUseCase $findByKeyTemplateNotificationUseCase,
        private SendEmailUseCase $sendEmailUseCase,
    ) {}

    /**
     * Create a new User entity.
     */
    public function execute(array $newUser): User
    {
        $user = $this->userRepository->create($newUser);
        try {
            $template = $this->findByKeyTemplateNotificationUseCase->execute('welcome-user', $user->getLanguage());
            $this->sendEmailUseCase->execute($template, $user);
        } catch (Throwable $th) {
            Log::channel('email')->error($th->getMessage());

            return $user;
        }

        return $user;
    }
}
