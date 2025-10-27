<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 * @property string $joined_at
 * @property string|null $deleted_at
 */
class GroupUser extends Model
{
    protected static string $table = 'group_users';
    protected static array $columns = ['user_id', 'group_id', 'joined_at', 'deleted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function expense(): HasMany
    {
        return $this->hasMany(Expense::class, 'group_id');
    }
}
