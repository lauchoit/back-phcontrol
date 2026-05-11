<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Controller;

use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\SendNotification\Application\UseCases\FindAllSendNotificationUseCase;
use Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Resources\SendNotificationResource;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;

readonly class SendNotificationController
{
    public function __construct(
        private FindAllSendNotificationUseCase $findAllSendNotificationUseCase,
    ) {}

    public function findAll(): JsonResponse
    {
        $filters = request()->only([
            'search',
        ]);

        $notifications = $this->findAllSendNotificationUseCase->execute($filters);

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: SendNotificationResource::collection($notifications));
    }
}
