<?php

namespace App\Models;

use App\Services\FileService;
use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\Model;
use Lib\Validations;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property float $amount
 * @property string $expense_date
 * @property int $register_by_user_id
 * @property int $group_id
 * @property string $created_at
 * @property int|null $register_payment_user_id
 * @property string $status
 * @property string|null $payment
 */
class Expense extends Model
{
    protected static string $table = 'expenses';
    protected static array $columns = [

        'title',
        'description',
        'amount',
        'expense_date',
        'register_by_user_id',
        'group_id',
        'created_at',
        'register_payment_user_id',
        'status',
        'payment'
    ];

    public function validates(): void
    {
        Validations::notEmpty("register_by_user_id", $this);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'register_by_user_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function registeredPaymentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'register_payment_user_id');
    }

    public function groupUser(): BelongsTo
    {
        return $this->belongsTo(GroupUser::class, 'group_id');
    }

    public function resource(): HasMany
    {
        return $this->hasMany(Resource::class, 'expenses_id');
    }

    public function expenseTags(): BelongsToMany
    {
        return $this->belongsToMany(ExpenseTags::class, 'expenses_id');
    }
}
