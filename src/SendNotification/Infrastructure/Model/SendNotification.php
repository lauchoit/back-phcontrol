<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Model;

use Carbon\Carbon;
use Database\Factories\SendNotificationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\SendNotificationSource;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model\TemplateNotification;

/**
 * @property string $id
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property array|null $cc
 * @property array|null $bcc
 * @property array|null $attachments
 * @property string|null $reply_to
 * @property string $channel
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read TemplateNotification $templateNotification
 *
 * @method static filter($query, array $filters)
 */
class SendNotification extends Model
{
    /** @use HasFactory<SendNotificationFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Create a new factory instance for the TemplateNotification model.
     */
    protected static function newFactory()
    {
        return SendNotificationFactory::new();
    }

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = SendNotificationSource::FIELDS;

    protected $casts = [
        'attachments' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scopes filters
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('to', 'like', '%'.$search.'%')
                ->orWhere('subject', 'like', '%'.$search.'%');
        });
    }
}
