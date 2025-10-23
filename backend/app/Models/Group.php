<?php

namespace App\Models;
use Core\Database\ActiveRecord\BelongsTo;

use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $owner_user_id
 * @property string $created_at
 */
class Group extends Model
{
    protected static string $table = '`groups`';
    protected static array $columns = ['name','description','owner_user_id', 'created_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }
}
