<?php

namespace App\Models;

use App\Services\FileService;
use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;
use SebastianBergmann\CodeCoverage\Node\File;

class Resource extends Model
{
    protected static string $table =  'resources';
    protected static array $columns = [
        'file_path',
        'expenses_id'
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expenses_id');
    }

    public function resourceFiles(): FileService
    {
        return new FileService($this, "uploads/resources/");
    }
}
