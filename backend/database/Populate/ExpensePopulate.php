<?php

namespace Database\Populate;

use App\Models\Expense;

class ExpensePopulate
{
    public static function populate()
    {
        self::createExpense(
            'Electricity Bill',
            'Monthly electricity bill payment',
            150.75,
            '2024-06-10',
            1,
            null,
            null,
            'pendente',
            null
        );

        $numberOfExpenses = 3;

        for ($i = 0; $i < $numberOfExpenses; $i++) {
            self::createExpense(
                'Expense ' . ($i + 1),
                'Description for expense ' . ($i + 1),
                rand(50, 500),
                date('Y-m-d', strtotime("+$i days")),
                $i + 1,
                null,
                null,
                'pendente',
                null
            );

            echo 'Expense ' . ($i + 1) . ' created successfully.' . PHP_EOL;
        }

        echo "Expenses populated successfully.\n";
    }

    public static function createExpense(
        string $title, 
        string $description, 
        float $amount, 
        string $expense_date,
        int $register_by_user_id, 
        ?int $group_user_id, 
        ?int $register_payment_user_id, 
        string $status, 
        ?string $payment
        ): void
    {
        try {
            $expense = new Expense([
                'title' => $title,
                'description' => $description,
                'amount' => $amount,
                'expense_date' => $expense_date,
                'register_by_user_id' => $register_by_user_id,
                'group_id' => $group_user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'register_payment_user_id' => $register_payment_user_id,
                'status' => $status,
                'payment' => $payment
            ]);
            
            $expense->save();
        } catch (\Exception $e) {
            echo "Error creating expense $title: " . $e->getMessage() . PHP_EOL;
        }
    }
}
