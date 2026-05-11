<?php

namespace Lauchoit\LaravelHexMod\User\Infrastructure\Model;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Lauchoit\LaravelHexMod\User\Domain\Entity\UserSource;
use Lauchoit\LaravelHexMod\User\Infrastructure\Policies\UserPolicy;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $id
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 */
#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable implements OAuthenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable;

    /**
     * Default guard used by Spatie permissions for this API user model.
     */
    protected string $guard_name = 'api';

    /**
     * Create a new factory instance for the User model.
     */
    protected static function newFactory()
    {
        return UserFactory::new();
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
    protected $fillable = UserSource::FIELDS;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    #[Scope]
    protected function filters(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if (in_array($key, UserSource::FIELDS, true)) {
                $query->orWhere($key, 'like', "%$value%");
            }
        }
    }
}
