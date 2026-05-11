<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\UseCases;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Lauchoit\LaravelHexMod\Auth\Domain\Exceptions\InvalidPasswordResetTokenException;
use Lauchoit\LaravelHexMod\User\Infrastructure\Model\User as UserModel;

class ResetPasswordUseCaseImpl
{
    public function execute(string $token, array $data): bool
    {
        $resetToken = DB::table('password_reset_tokens')
            ->get()
            ->first(fn ($record) => Hash::check($token, $record->token));

        if (! $resetToken) {
            throw new InvalidPasswordResetTokenException;
        }

        $user = UserModel::where('email', $resetToken->email)->first();
        if (! $user || ! Password::broker()->tokenExists($user, $token)) {
            throw new InvalidPasswordResetTokenException;
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(60),
        ])->save();

        Password::broker()->deleteToken($user);

        return true;
    }
}
