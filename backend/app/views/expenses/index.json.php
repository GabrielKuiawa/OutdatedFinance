<?php

$expensesToJson = [];
foreach ($expenses as $expense){
    $expensesToJson[] = [
        'id' =>$expense->id,
        'title' => $expense->title,
        'description' => $expense->description,
        'amount' => $expense->amount,
        'expense_date' => $expense->expense_date,
        'register_by_user_id' => $expense->register_by_user_id,
        'group_id' => $expense->group_id,
        'register_payment_user_id' => $expense->register_payment_user_id,
        'status' => $expense->status,
        'payment' => $expense->payment,
        'created_at' => $expense->created_at,
    ];

}
$json['expenses'] = $expensesToJson; 