<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: fn () => [
            require base_path('src/User/Infrastructure/Routes/UserRoutes.php'),
            require base_path('src/Auth/Infrastructure/Routes/AuthRoutes.php'),
            require base_path('src/AccessControl/Infrastructure/Routes/AccessControlRoutes.php'),
        ],
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e) {
            if (($e instanceof RouteNotFoundException && ! config('app.debug')) ||
                ($e instanceof NotFoundHttpException && ! config('app.debug'))) {
                return response('error', 500)
                    ->header('Content-Type', 'text/plain');
            }

            if ($e instanceof AuthenticationException) {
                return ApiResponse::error(ApiResponse::$USER_UNAUTHENTICATED, code: 401);
            }

            if ($e instanceof AccessDeniedHttpException) {
                return ApiResponse::error(ApiResponse::$USER_UNAUTHORIZED, code: 403);
            }

            if ($e instanceof QueryException) {
                return ApiResponse::error(ApiResponse::$DATABASE_EXCEPTION, data: $e->getMessage(), code: 403);
            }
        });
    })->create();
