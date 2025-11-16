<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;


/**
 * @property int $id
 * @property string $name
 * @property string $user_id
 */
class Tag extends Model
{
    protected static string $table = 'tags';

    protected array $fillable = [
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
}
