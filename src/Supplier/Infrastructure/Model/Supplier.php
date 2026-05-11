<?php

namespace Lauchoit\LaravelHexMod\Supplier\Infrastructure\Model;

use Database\Factories\SupplierFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lauchoit\LaravelHexMod\Supplier\Domain\Entity\SupplierSource;
use Lauchoit\LaravelHexMod\Supplier\Infrastructure\Policies\SupplierPolicy;

/**
 * @property string $id
 * @property string $name
 * @property ?string $phone
 * @property string $created_at
 * @property string $updated_at
 * @property ?string $deleted_at
 */
#[UsePolicy(SupplierPolicy::class)]
class Supplier extends Model
{
    /** @use HasFactory<SupplierFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Create a new factory instance for the Supplier model.
     */
    protected static function newFactory()
    {
        return SupplierFactory::new();
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
    protected $fillable = SupplierSource::FIELDS;

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
            'deleted_at' => 'datetime',
        ];
    }
}
