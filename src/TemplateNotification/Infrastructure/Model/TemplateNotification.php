<?php

namespace Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Model;

use Database\Factories\TemplateNotificationFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Lauchoit\LaravelHexMod\TemplateNotification\Domain\Entity\TemplateNotificationSource;
use Lauchoit\LaravelHexMod\TemplateNotification\Infrastructure\Policies\TemplateNotificationPolicy;

/**
 * @property string $id
 * @property string $key
 * @property string $locale
 * @property string $subject
 * @property string $body_html
 * @property int $version
 * @property bool $is_active
 * @property string $variables
 * @property string $created_at
 * @property string $updated_at
 */
#[UsePolicy(TemplateNotificationPolicy::class)]
class TemplateNotification extends Model
{
    /** @use HasFactory<TemplateNotificationFactory> */
    use HasFactory, HasUuids;

    protected $table = 'template_notifications';

    /**
     * Create a new factory instance for the TemplateNotification model.
     */
    protected static function newFactory()
    {
        return TemplateNotificationFactory::new();
    }

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = TemplateNotificationSource::FIELDS;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_active' => 'bool',
        ];
    }
}
