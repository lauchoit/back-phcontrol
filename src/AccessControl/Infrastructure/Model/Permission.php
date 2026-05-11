<?php

namespace Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Model;

use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Lauchoit\LaravelHexMod\AccessControl\Domain\Entity\PermissionSource;
use Lauchoit\LaravelHexMod\AccessControl\Infrastructure\Policies\PermissionPolicy;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @property string $id
 * @property string $name
 * @property string $guardName
 * @property string $created_at
 * @property string $updated_at
 */
#[UsePolicy(PermissionPolicy::class)]
class Permission extends SpatiePermission
{
    /** @use HasFactory<PermissionFactory> */
    use HasFactory, HasUuids;

    /**
     * Create a new factory instance for the Permission model.
     */
    protected static function newFactory()
    {
        return PermissionFactory::new();
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

    public function uniqueIds(): array
    {
        return ['id'];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = PermissionSource::FIELDS;

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
        ];
    }
}
