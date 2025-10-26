<?php

namespace Tests\Unit\Models\Expense;

use App\Models\Expense;
use Tests\TestCase;

class ExpenseTest extends TestCase 
{
    public Expense $expense;

    public function setUp(): void
    {
        parent::setUp();

        $this->expense = new Expense();
        $this->expense->title = 'Teste de despesa';
        $this->expense->description = 'Descrição da despesa';
        $this->expense->amount = 100.50;
        $this->expense->expense_date = '2025-10-25';
        $this->expense->register_by_user_id = 1;
        $this->expense->group_id = 2;
        $this->expense->status = 'Pendente';
        $this->expense->created_at = date('Y-m-d H:i:s');
    }

   
    public function test_expense_properties(): void
    {
        $this->assertEquals('Teste de despesa', $this->expense->title);
        $this->assertEquals(100.50, $this->expense->amount);
        $this->assertEquals('Pendente', $this->expense->status);
    }

    public function test_belongs_to_user(): void
    {
        $relation = $this->expense->user();
        $this->assertInstanceOf(\Core\Database\ActiveRecord\BelongsTo::class, $relation);
    }

    public function test_table_name_is_correct(): void
    {
        $reflection = new \ReflectionClass(Expense::class);
        $property = $reflection->getProperty('table');
        $property->setAccessible(true);
        $this->assertEquals('expenses', $property->getValue());
    }

    public function test_belongs_to_group(): void
    {
        $relation = $this->expense->group();
        $this->assertInstanceOf(\Core\Database\ActiveRecord\BelongsTo::class, $relation);
    }


    public function test_belongs_to_registered_payment_by(): void
    {
        $relation = $this->expense->registeredPaymentBy();
        $this->assertInstanceOf(\Core\Database\ActiveRecord\BelongsTo::class, $relation);
    }

    public function test_belongs_to_group_user(): void
    {
        $relation = $this->expense->groupUser();
        $this->assertInstanceOf(\Core\Database\ActiveRecord\BelongsTo::class, $relation);
    }
}