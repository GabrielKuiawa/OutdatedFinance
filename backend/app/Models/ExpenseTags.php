<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\Model;


class ExpenseTags extends Model
{
    protected static string $table = 'expenses_tags';
    protected static array $columns = ['tag_id', 'expenses_id'];

    public function tag(): BelongsTo 
    {
        return $this->belongsTo(Tag::class, 'tag_id');

    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expenses_id');
    }
}