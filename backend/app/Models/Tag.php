<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\Model;
    /**
     * @property string $name
     * @property int $user_id
     */

class Tag extends Model
{
    protected static string $table = 'tags';
     /**
     * @var array<int, string>
     */

    protected static array $columns = [
        'name',
        'user_id',
    ];

    public function validate(): void
    {

        if (empty($this->name)) {
            $this->addError('name', 'O nome da tag é obrigatório.');
        }

        if (strlen($this->name) > 255) {
            $this->addError('name', 'O nome da tag deve ter no máximo 255 caracteres.');
        }

        if (empty($this->user_id)) {
            $this->addError('user_id', 'Usuário não informado.');
        }
    }

    //tag pertence a um usuário

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function expenseTags(): BelongsToMany
    {
        return $this->BelongsToMany(ExpenseTags::class, 'tag_id');
    }
        
}
