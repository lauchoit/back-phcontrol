<?php

namespace Lauchoit\LaravelHexMod\Auth\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\Auth\Application\UseCases\ForgetPasswordUserCase;
use Lauchoit\LaravelHexMod\Auth\Application\UseCases\LoginAuthUseCase;
use Lauchoit\LaravelHexMod\Auth\Application\UseCases\LogoutAuthUseCase;
use Lauchoit\LaravelHexMod\Auth\Application\UseCases\MeAuthUseCase;
use Lauchoit\LaravelHexMod\Auth\Application\UseCases\ResetPasswordUseCase;
use Lauchoit\LaravelHexMod\Auth\Domain\Exceptions\InvalidCredentialsException;
use Lauchoit\LaravelHexMod\Auth\Domain\Exceptions\InvalidPasswordResetTokenException;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Requests\LoginAuthRequest;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Requests\ResetPasswordRequest;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Resources\AuthResource;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use Lauchoit\LaravelHexMod\User\Domain\Exceptions\UserNotFoundException;

class AuthController extends Controller
{
    public function __construct(
        private readonly LoginAuthUseCase $loginAuthUseCase,
        private readonly MeAuthUseCase $meAuthUseCase,
        private readonly LogoutAuthUseCase $logoutAuthUseCase,
        private readonly ForgetPasswordUserCase $forgetPasswordUserCase,
        private readonly ResetPasswordUseCase $resetPasswordUseCase,
    ) {}

    /**
     * Make authenticate user with new Token.
     */
    public function login(LoginAuthRequest $request): JsonResponse
    {
        try {
            $auth = $this->loginAuthUseCase->execute($request->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_LOGIN, data: AuthResource::make($auth));
        } catch (\Exception $error) {
            if ($error instanceof InvalidCredentialsException) {
                return ApiResponse::error(message: $error->getMessage(), code: 401);
            }
            throw $error;
        }
    }

    /**
     * Return the authenticated user with new Token.
     */
    public function me(): JsonResponse
    {
        $auth = $this->meAuthUseCase->execute();

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: AuthResource::make($auth));
    }

    /**
     * Logout to authenticate user.
     */
    public function logout(): JsonResponse
    {
        $this->logoutAuthUseCase->execute();

        return ApiResponse::success(message: ApiResponse::$SUCCESS_LOGOUT, data: true);
    }

    public function forgetPassword($email): JsonResponse
    {
        try {
            $response = $this->forgetPasswordUserCase->execute($email);
            $data = [
                'email' => $email,
                'urlRecover' => $response,
            ];

            return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: $data, code: 201);
        } catch (\Exception $error) {
            if ($error instanceof UserNotFoundException) {
                return ApiResponse::error(message: $error->getMessage(), code: 404);
            }
            throw $error;
        }
    }

    public function resetPassword(ResetPasswordRequest $request, string $token): JsonResponse
    {
        try {
            $response = $this->resetPasswordUseCase->execute($token, $request->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: $response);
        } catch (\Exception $error) {
            if ($error instanceof InvalidPasswordResetTokenException) {
                return ApiResponse::error(message: $error->getMessage(), code: 400);
            }
            throw $error;
        }
    }
}
