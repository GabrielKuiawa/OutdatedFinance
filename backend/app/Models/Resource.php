<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class Resource extends Model
{
    // nome da tabela no banco
    protected static string $table = 'resources';

    // colunas que podem ser preenchidas via create/update
    protected array $fillable = [
        'file_path',
        'expenses_id'
    ];

    // relacionamento N:1 (muitos resources pertencem a uma expense)
    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expenses_id');
    }
}
