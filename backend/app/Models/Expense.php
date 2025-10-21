<?php

namespace App\Models;

use Core\Database\Database;
use App\Enums\ExpenseStatus;
use App\Enums\ExpensePayment;

class Expense {
    public ?int $id;
    public string $title;
    public string $description;
    public float $amount;
    public string $expense_date;
    public ?int $register_by_user_id;
    public ?int $group_user_id;
    public ?int $register_payment_user_id;
    // public ExpenseStatus $status;
    // public ?ExpensePayment $payment;
    public $status; 
    public $payment;
    public string $created_at;

    public function __construct(
        int $id = null,
        string $title = '',
        string $description = '',
        float $amount = 0,
        string $expense_date = null,
        int $register_by_user_id = null,
        int $group_user_id = null,
        int $register_payment_user_id = null,
        // ExpenseStatus|string $status = ExpenseStatus::PENDENTE,
        // ExpensePayment|string $payment = null,
        string $status = 'pendente',
         string $payment = null,
        string $created_at = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->amount = $amount;
        $this->expense_date = $expense_date ?? date('Y-m-d');
        $this->register_by_user_id = $register_by_user_id;
        $this->group_user_id = $group_user_id;
        $this->register_payment_user_id = $register_payment_user_id;
        // $this->status = is_string($status) ? ExpenseStatus::from($status) : $status;
        // $this->payment = is_string($payment) ? ExpensePayment::from($payment) : $payment;
        $this->status = $status; 
        $this->payment = $payment;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }

    public function create(): void {
        $pdo = Database::getDatabaseConn();
        $sql = 'INSERT INTO expenses 
                (title, description, amount, expense_date, register_by_user_id, group_user_id, register_payment_user_id, status, payment)
                VALUES (:title, :description, :amount, :expense_date, :register_by_user_id, :group_user_id, :register_payment_user_id, :status, :payment)';
        $stmt = $pdo->prepare($sql);
        //bindValue copia o valor sem referência
        $stmt->bindValue(':title', $this->title);
        $stmt->bindValue(':description', $this->description);
        $stmt->bindValue(':amount', $this->amount);
        $stmt->bindValue(':expense_date', $this->expense_date);
        $stmt->bindValue(':register_by_user_id', $this->register_by_user_id);
        $stmt->bindValue(':group_user_id', $this->group_user_id);
        $stmt->bindValue(':register_payment_user_id', $this->register_payment_user_id);
        // $stmt->bindValue(':status', $this->status->value);
        // $stmt->bindValue(':payment', $this->payment?->value);
        $stmt->bindParam(':status', $this->status); 
        $stmt->bindParam(':payment', $this->payment);

        $stmt->execute();
        $this->id = (int) $pdo->lastInsertId();
    }

    public function update(): void {
        $pdo = Database::getDatabaseConn();
        $sql = 'UPDATE expenses 
                SET title = :title, 
                    description = :description, 
                    amount = :amount, 
                    expense_date = :expense_date, 
                    status = :status, 
                    payment = :payment 
                WHERE id = :id';

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $this->id);
        $stmt->bindValue(':title', $this->title);
        $stmt->bindValue(':description', $this->description);
        $stmt->bindValue(':amount', $this->amount);
        $stmt->bindValue(':expense_date', $this->expense_date);
        // $stmt->bindValue(':status', $this->status->value);      // Enum
        // $stmt->bindValue(':payment', $this->payment?->value);   // Enum ou null
        $stmt->bindParam(':status', $this->status); 
        $stmt->bindParam(':payment', $this->payment);

        $stmt->execute();
    }


    public static function getAll(): array {
        $pdo = Database::getDatabaseConn();
        $resp = $pdo->query('SELECT * FROM expenses');
        $expenses = [];

        foreach ($resp as $row) {
            $expenses[] = new Expense(
                $row['id'],
                $row['title'],
                $row['description'],
                $row['amount'],
                $row['expense_date'],
                $row['register_by_user_id'],
                $row['group_user_id'],
                $row['register_payment_user_id'],
                $row['status'],
                $row['payment']
            );
        }

        return $expenses;
    }

    public static function findById(int $id): ?Expense {
        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare('SELECT * FROM expenses WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return null;
        }

        $row = $stmt->fetch();
        return new Expense(
            $row['id'],
            $row['title'],
            $row['description'],
            $row['amount'],
            $row['expense_date'],
            $row['register_by_user_id'],
            $row['group_user_id'],
            $row['register_payment_user_id'],
            $row['status'],
            $row['payment']
        );
    }

    public function destroy(): bool {
        $pdo = Database::getDatabaseConn();
        $stmt = $pdo->prepare('DELETE FROM expenses WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
