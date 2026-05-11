<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\PermissionRepository;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Repository\RoleRepository;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\PermissionRepositoryImpl;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Repository\RoleRepositoryImpl;
use Lauchoit\LaravelHexMod\Auth\Domain\Repository\AuthRepository;
use Lauchoit\LaravelHexMod\Auth\Infrastructure\Repository\AuthRepositoryImpl;
use Lauchoit\LaravelHexMod\Product\Domain\Repository\ProductRepository;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Repository\ProductRepositoryImpl;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Repository\SendNotificationRepository;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Repository\SendNotificationRepositoryImpl;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Repository\TemplateNotificationRepository;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Repository\TemplateNotificationRepositoryImpl;
use Lauchoit\LaravelHexMod\User\Domain\Repository\UserRepository;
use Lauchoit\LaravelHexMod\User\Infrastructure\Repository\UserRepositoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ProductRepository::class, ProductRepositoryImpl::class);
        $this->app->bind(AuthRepository::class, AuthRepositoryImpl::class);
        $this->app->bind(RoleRepository::class, RoleRepositoryImpl::class);
        $this->app->bind(PermissionRepository::class, PermissionRepositoryImpl::class);
        $this->app->bind(SendNotificationRepository::class, SendNotificationRepositoryImpl::class);
        $this->app->bind(TemplateNotificationRepository::class, TemplateNotificationRepositoryImpl::class);
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
