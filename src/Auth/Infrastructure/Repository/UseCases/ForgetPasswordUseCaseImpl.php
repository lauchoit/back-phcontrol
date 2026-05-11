<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases;

use Illuminate\Support\Facades\Password;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class ForgetPasswordUseCaseImpl
{
    /**
     * @param  string  $email
     */
    public function execute($email): string
    {
        $token = Password::broker()->createToken(UserModel::where('email', $email)->first());

        return config('app.frontend_url').'/reset-password/'.$token;
    }
}
