<?php

namespace Lauchoit\LaravelHexMod\Product\Infrastructure\Model;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Lauchoit\LaravelHexMod\Product\Domain\Entity\ProductSource;
use Lauchoit\LaravelHexMod\Product\Infrastructure\Policies\ProductPolicy;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_active
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 */
#[UsePolicy(ProductPolicy::class)]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * Create a new factory instance for the Product model.
     */
    protected static function newFactory()
    {
        return ProductFactory::new();
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
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ProductSource::FIELDS;

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
