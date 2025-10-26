<?php

namespace App\Models;

use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\BelongsToMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property string $avatar
 * @property string $role
 * @property string $created_at
 * @property string $deleted_at
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'encrypted_password', 'avatar', 'role', 'created_at', 'deleted_at'];

    protected ?string $password = null;
    protected ?string $password_confirmation = null;

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'register_by_user_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_user_id');
    }

    public function memberGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_users', 'user_id', 'group_id');
    }



    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);

        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::passwordConfirmation($this);
        }
    }

    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }

        return password_verify($password, $this->encrypted_password);
    }

    public static function findByEmail(string $email): User | null
    {
        return User::findBy(['email' => $email]);
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}
