<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Lauchoit\LaravelHexMod\Shared\Responses\ApiResponse;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\CreateTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\DeleteByIdTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\FindAllTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\FindByIdTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\FindByKeyTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Application\UseCases\UpdateByIdTemplateNotificationUseCase;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Exceptions\TemplateNotificationNotFoundException;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification as MailTemplateModel;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Requests\CreateTemplateNotificationRequest;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Requests\UpdateTemplateNotificationRequest;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Resources\TemplateNotificationResource;

class TemplateNotificationController extends Controller
{
    public function __construct(
        private readonly CreateTemplateNotificationUseCase $createMailTemplateUseCase,
        private readonly FindAllTemplateNotificationUseCase $findAllMailTemplateUseCase,
        private readonly FindByIdTemplateNotificationUseCase $findByIdMailTemplateUseCase,
        private readonly DeleteByIdTemplateNotificationUseCase $deleteByIdMailTemplateUseCase,
        private readonly UpdateByIdTemplateNotificationUseCase $updateByIdMailTemplateUseCase,
        private readonly FindByKeyTemplateNotificationUseCase $findByKeyMailTemplateUseCase,
    ) {}

    public function create(CreateTemplateNotificationRequest $mailTemplate): JsonResponse
    {
        $this->authorize('create', MailTemplateModel::class);
        $newMailTemplate = $this->createMailTemplateUseCase->execute($mailTemplate->validated());

        return ApiResponse::success(message: ApiResponse::$SUCCESS_CREATED, data: TemplateNotificationResource::make($newMailTemplate), code: 201);
    }

    public function findAll(): JsonResponse
    {
        $this->authorize('findAll', MailTemplateModel::class);
        $mailTemplates = $this->findAllMailTemplateUseCase->execute();

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: TemplateNotificationResource::collection($mailTemplates));
    }

    /**
     * Find a TemplateNotification entity by its ID.
     *
     * @param  string  $mailTemplateId
     */
    public function findById($mailTemplateId): JsonResponse
    {
        $this->authorize('findById', [MailTemplateModel::class]);
        $mailTemplate = $this->findByIdMailTemplateUseCase->execute($mailTemplateId);
        if (! $mailTemplate) {
            return ApiResponse::error(message: ApiResponse::$ERROR_NOT_FOUND, code: 404);
        }

        return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: TemplateNotificationResource::make($mailTemplate));
    }

    /**
     * Delete a TemplateNotification entity by its ID.
     *
     * @param  string  $mailTemplateId
     */
    public function deleteById($mailTemplateId): JsonResponse
    {
        $this->authorize('deleteById', [MailTemplateModel::class]);
        try {
            $deleted = $this->deleteByIdMailTemplateUseCase->execute($mailTemplateId);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_DELETED, data: $deleted);
        } catch (TemplateNotificationNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Update a TemplateNotification entity by its ID.
     *
     * @param  string  $mailTemplateId
     */
    public function updateById($mailTemplateId, UpdateTemplateNotificationRequest $data): JsonResponse
    {
        $this->authorize('updateById', [MailTemplateModel::class]);
        try {
            $mailTemplateUpdated = $this->updateByIdMailTemplateUseCase->execute($mailTemplateId, $data->validated());

            return ApiResponse::success(message: ApiResponse::$SUCCESS_UPDATED, data: TemplateNotificationResource::make($mailTemplateUpdated));
        } catch (TemplateNotificationNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }

    /**
     * Find a TemplateNotification entity by its ID.
     *
     * @param  string  $key
     * @param  string  $language
     */
    public function findByKey($key, $language): JsonResponse
    {
        $this->authorize('findByKey', [MailTemplateModel::class]);
        try {
            $mailTemplate = $this->findByKeyMailTemplateUseCase->execute($key, $language);

            return ApiResponse::success(message: ApiResponse::$SUCCESS_SEARCH, data: TemplateNotificationResource::make($mailTemplate));
        } catch (TemplateNotificationNotFoundException $e) {
            return ApiResponse::error(message: $e->getMessage(), code: 404);
        }
    }
}
