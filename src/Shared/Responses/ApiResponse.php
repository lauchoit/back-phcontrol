<?php

namespace Lauchoit\LaravelHexMod\Shared\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * RESPONSES SUCCESS
     */
    public static $SUCCESS_CREATED = 'success.created';

    public static $SUCCESS_SEARCH = 'success.search';

    public static $SUCCESS_DELETED = 'success.deleted';

    public static $SUCCESS_UPDATED = 'success.updated';

    public static $SUCCESS_LOGOUT = 'success.logout';

    public static $SUCCESS_LOGIN = 'success.login';

    public static $SUCCESS_ADDED = 'success.added';

    /**
     * RESPONSES ERRORS
     */
    public static $ERROR_NOT_FOUND = 'error.not_found';

    public static $ERROR_VALIDATION_FAILED = 'error.validation.failed';

    public static $USER_UNAUTHENTICATED = 'user.unauthenticated';

    public static $USER_UNAUTHORIZED = 'user.unauthorized';

    public static $USER_NOT_FOUND = 'user.not_found';

    public static $DATABASE_EXCEPTION = 'database.problem';

    /**
     * @param  string  $message
     */
    public static function success($message, mixed $data = null, int $code = 200): JsonResponse
    {
        return self::responseGeneric($message, $data, $code, $ok = true);
    }

    /**
     * @param  string  $message
     */
    public static function error($message, mixed $data = null, int $code = 400): JsonResponse
    {
        return self::responseGeneric($message, $data, $code, $ok = false);
    }

    /**
     * @param  string  $message
     */
    private static function responseGeneric($message, mixed $data, int $code, bool $ok): JsonResponse
    {
        return response()->json([
            'ok' => $ok,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
